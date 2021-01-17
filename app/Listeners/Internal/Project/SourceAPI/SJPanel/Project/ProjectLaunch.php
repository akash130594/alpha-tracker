<?php
namespace App\Listeners\Internal\Project\SourceAPI\SJPanel\Project;

use App\Events\Internal\Project\SourceAPI\SourceAPIEvents;
use App\Listeners\Internal\Project\SourceAPI\SJPanel\SJPanelBase;
use App\Models\Project\Project;
use App\Models\Project\ProjectSurvey;
use App\Models\Source\VendorApiMapping;
use App\Repositories\Internal\Project\ProjectSurveyRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Session;

class ProjectLaunch extends SJPanelBase implements ShouldQueue
{
    public function launchProject(SourceAPIEvents $sourceAPIEvent)
    {
        $actionUrl = "{{url}}/api/project/change-status/{{SurveyNumber}}";
        $expectedStatusCode = 200;
        $project = $sourceAPIEvent->project;
        $projectVendor = $project->hasSource($this->apaceSourceCode);
        if ( !$projectVendor ) {
            return;
        }
        $projectSurveyRepo = new ProjectSurveyRepository();
        $project_surveys = $projectSurveyRepo->getProjectVendorSurvey([$projectVendor->id]);
        if ( empty($project_surveys) ) {
            return;
        }
        $vendorMapping = VendorApiMapping::where('code', '=', $this->apaceSourceCode)->first();
        $mappedProjectStatusCodes = array_column($vendorMapping->project_statuses, 'SOURCE_MAP_CODE', 'SJ_ID');
        foreach ($project_surveys as $survey) {
            $this->survey_number = $survey->vendor_survey_code;
            $sjpanelSurveyInfo['survey_status_code'] = $mappedProjectStatusCodes[$project->status_id];
            $callApiData = [
                'action_url' => $actionUrl,
                'expected_status_code' => $expectedStatusCode,
                'post_data' => $sjpanelSurveyInfo,
            ];
            $response = $this->callSJPanelAPI($callApiData);
            if($response){
                $survey->vendor_survey_code = $this->survey_number = $response->Project->code;
                $survey->status_id = $project->status_id;
                $survey->status_label = $project->status_label;
                $survey->save();
                Session::flash('flash_success', 'SJ Panel Launched #'.$response->Project->code);

            }else{
                Session::flash('flash_danger', 'SJ Panel Launch Error Occured');
            }

        }
        return;

    }

    public function callSJPanelAPI($apiData)
    {
        $actionUrl = $apiData['action_url'];
        $statusCode = $apiData['expected_status_code'];
        $postData = $apiData['post_data'];
        $finalUrl = $this->applyUrlChange($actionUrl);
        $requestArray = $this->getApiHeaders();
        $requestArray['json'] = $postData;
        try {
            $client = new Client();

            $response = $client->post($finalUrl, $requestArray);

            $responseCode = $response->getStatusCode();

            if ($responseCode !== $statusCode) {
                $responseBody = $response->getBody();
                $responseContent = $responseBody->getContents();
                dd('status', $response, $responseBody, json_decode($responseContent));
                return false;
            }
            $responseBody = $response->getBody();
            $responseContent = $responseBody->getContents();

            //dd($responseContent);
            //dd($response, $responseBody, json_decode($responseContent));
            return json_decode($responseContent);
        } catch (ServerException $e) {
            dd('ServerException, error on launching in SJ Panel', $e);
        } catch (GuzzleException $e) {
            dd('GuzzleException', $e);
        }
    }

}
