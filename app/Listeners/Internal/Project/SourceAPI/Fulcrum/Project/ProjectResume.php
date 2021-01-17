<?php
/*******************************************************************************
 * Copyright (c) 2019. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
 * Morbi non lorem porttitor neque feugiat blandit. Ut vitae ipsum eget quam lacinia accumsan.
 * Etiam sed turpis ac ipsum condimentum fringilla. Maecenas magna.
 * Proin dapibus sapien vel ante. Aliquam erat volutpat. Pellentesque sagittis ligula eget metus.
 * Vestibulum commodo. Ut rhoncus gravida arcu.
 ******************************************************************************/

namespace App\Listeners\Internal\Project\SourceAPI\Fulcrum\Project;


use App\Events\Internal\Project\SourceAPI\SourceAPIEvents;
use App\Listeners\Internal\Project\SourceAPI\Fulcrum\FulcrumBase;
use App\Models\Project\Project;
use App\Models\Project\ProjectSurvey;
use App\Models\Source\VendorApiMapping;
use App\Repositories\Internal\Project\ProjectSurveyRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Session;

class ProjectResume extends FulcrumBase //implements ShouldQueue
{
    public function resumeProject(SourceAPIEvents $sourceAPIEvent)
    {
        $actionUrl = "{{url}}/Demand/v1/Surveys/Update/{{SurveyNumber}}";
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

            $fulcrumSurveyInfo = $this->getProjectInfo($survey->vendor_survey_code);
            $fulcrumSurveyInfo['SurveyStatusCode'] = $mappedProjectStatusCodes[$project->status_id];

            $callApiData = [
                'action_url' => $actionUrl,
                'expected_status_code' => $expectedStatusCode,
                'post_data' => $fulcrumSurveyInfo,
            ];

            $response = $this->callFulcrumAPI($callApiData);
            if($response){
                $survey->status_id = $project->status_id;
                $survey->status_label = $project->status_label;
                $survey->save();

                //$this->createQualification($project, $survey, $response);

                Session::flash('flash_success', 'FL Resume #'.$response->Survey->SurveyNumber);

            }else{
                Session::flash('flash_danger', 'FL Resume Error Occured');
            }

        }
    }

    public function callFulcrumAPI($apiData)
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
        }catch(ServerException $e){
            dd('ServerException, error on launching in FL', $e);
        } catch (GuzzleException $e) {
            dd('GuzzleException', $e);
        }
    }

    public function createQualification(Project $project, ProjectSurvey $projectSurvey, $apiResponse)
    {
        dd('create Qualification');
    }
}
