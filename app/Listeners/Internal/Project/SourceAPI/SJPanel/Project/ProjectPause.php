<?php
/**
 * Created by PhpStorm.
 * User: Sample Junction
 * Date: 3/28/2019
 * Time: 1:17 AM
 */

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
class ProjectPause extends SJPanelBase implements ShouldQueue
{
    public function pauseProject(SourceAPIEvents $sourceAPIEvent)
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
        if (empty($project_surveys)) {
            return;
        }
        $vendorMapping = VendorApiMapping::where('code', '=', $this->apaceSourceCode)->first();
        $mappedSurveyTypeIDs = collect($vendorMapping->country_language);
        $mappedProjectStatusCodes = array_column($vendorMapping->project_statuses, 'SOURCE_MAP_CODE', 'SJ_ID');
        foreach ($project_surveys as $survey) {
            $this->survey_number = $survey->vendor_survey_code;
            $sjpanelInfo['survey_status_code'] = $mappedProjectStatusCodes[$project->status_id];
            $callApiData = [
                'action_url' => $actionUrl,
                'expected_status_code' => $expectedStatusCode,
                'post_data' => $sjpanelInfo,
            ];
            $response = $this->callSJPanelAPI($callApiData);
            if($response){
                $survey->vendor_survey_code = $response->Project->code;
                $survey->status_id = $project->status_id;
                $survey->status_label = $project->status_label;
                $survey->save();
                Session::flash('flash_success', 'FL Paused #'.$response->Project->code);
            }else{
                Session::flash('flash_danger', 'FL Pause Error Occured');
            }
        }
    }
    public function callSJPanelAPI($apiData)
    {
        $actionUrl = $apiData['action_url'];
        $statusCode = $apiData['expected_status_code'];
        $postData = $apiData['post_data'];
        $finalUrl = $this->applyUrlChange($actionUrl);
        $requestArray = $this->getApiHeaders();
        $requestArray['json'] = $postData;
        try{
            $client = new Client();
            $response = $client->put($finalUrl, $requestArray);
            $responseCode = $response->getStatusCode();

            if ($responseCode !== $statusCode) {
                return false;
            }
            $responseBody = $response->getBody();
            $responseContent = $responseBody->getContents();
            //dd($response, $responseBody, json_decode($responseContent));
            return json_decode($responseContent);
        }catch(ServerException $e){
            dd('ServerException, error on launching in FL', $e);
        } catch (GuzzleException $e) {
            dd('GuzzleException', $e);
        }
    }
}
