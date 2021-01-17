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
use App\Listeners\Internal\Project\SourceAPI\SJPanel\traits\methods\SJPanelMethods;
use App\Listeners\Internal\Project\SourceAPI\SJPanel\traits\methods\SJPanelQuotaMethods;
use App\Models\Source\VendorApiMapping;
use App\Repositories\Internal\Project\ProjectQuotaRepository;
use App\Repositories\Internal\Project\ProjectVendorRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Repositories\Internal\Project\ProjectSurveyRepository;
use Carbon\Carbon;

class ProjectCreate extends SJPanelBase //implements ShouldQueue
{
    use SJPanelQuotaMethods;

    public $project;
    public function createProject(SourceAPIEvents $sourceAPIEvent)
    {
        $actionUrl = "{{url}}/api/project/create";
        $expectedStatusCode = 201;
        $project = $this->project = $sourceAPIEvent->project;
        $projectVendor = $project->hasSource($this->apaceSourceCode);

        /*If Project Don't have SJPanel Vendor then SKIP*/
        if ( !$projectVendor ) {
            return;
        }
        Log::alert('SJPanel Launch  Initiated #'.$project->code);
        $projectSurveyRepo = new ProjectSurveyRepository();
        $project_surveys = $projectSurveyRepo->getProjectVendorSurvey([$projectVendor->id]);
        if (empty($project_surveys)) {
            return;
        }
        //Log::debug('SJPANEL Project Surveys To Deploy', [print_r($project_surveys, true)]);
        $vendorMapping = VendorApiMapping::where('code', '=', $this->apaceSourceCode)->first();

        $country_langs = collect($vendorMapping->country_language);
        $language_item = $country_langs
            ->where('SJ_CON_CODE', '=', $project->country_code)
            ->where('SJ_LANG_CODE', '=', $project->language_code)
            ->first();
        $mappedProjectStatusCodes = array_column($vendorMapping->project_statuses, 'SOURCE_MAP_CODE', 'SJ_ID');

        foreach ($project_surveys as $survey) {
            $country_data = explode('-', $language_item['SJPL_CON_LANG_CODE']);
            $creationData = [
                "live_url" => $survey->generateSurveyLiveLink(),   //Our Live Survey URL
                "test_url" => $survey->generateSurveyTestLink(),   //string    Link to client survey for testing purposes.
                "survey_name" => $project->code."-".$survey->code." - ".Carbon::now()->toDateTimeString(),     //string    External name of the survey. This name may be exposed to respondents.
                "country_code" => $country_data[0],
                "language_code" => $country_data[1],
                "survey_status_code" => $mappedProjectStatusCodes[$project->status_id],     //string    Code associated with the current status of the survey
                "quota" => $project->quota,     //int   Total number of completes needed
                "client_cpi" => 1,     //int   Total number of completes needed
                "cpi" => 1,  //double    Gross payout per complete. This value is before any applicable commissions or fees
                "loi" => $project->loi,
                "ir" => (int) $project->ir,       //int   Estimated incidence rate for the survey.
            ];
            $finalData = array_merge($this->projectCreateData(), $creationData);
            $callApiData = [
                'action_url' => $actionUrl,
                'expected_status_code' => $expectedStatusCode,
                'post_data' => $finalData,
            ];

            $response = $this->callSJPanelAPI($callApiData);

            /*$Panelproject = new \stdClass();
            $Panelproject->code = '190426001USEN';

            $response = new \stdClass();
            $response->Project = $Panelproject;*/

            if($response){
                $survey->vendor_survey_code = $this->survey_number = $response->Project->code;
                $survey->status_id = $project->status_id;
                $survey->status_label = $project->status_label;
                $survey->save();
                $this->createQuotaQualifications();
                Log::alert('SJPanel Launched #'.$response->Project->code);
            }else{
                Log::alert('SJPanel Launch debug Occured - '. $project->code);
            }
        }
        return;
    }

    public function projectCreateData()
    {
        $data = [
            "is_dedupe" => false,   //true enables Relevant ID dedupe security.
            "is_geoip" => false,   //true enables RelevantID GeoIP security to determine respondent geographical locatio
            "is_active" => true,   //NEED TO CONFIRM   - Indicates if a survey is active or inactive in Marketplace database.
            "unique_ip_address" => true,     //boolean true enables IP deduplication on a survey preventing a respondent with the same IP address from entering more than once.
            "unique_pid" => true,        //boolean   true enables PID deduplication on a survey preventing a respondent with the same PID from entering more than once.
            "survey_priority" => 1,       //int   Survey priority from 1-11 (1 being the highest).
        ];

        return $data;
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
            $response = $client->post($finalUrl, $requestArray);
            $responseCode = $response->getStatusCode();
            if ($responseCode !== $statusCode) {
                dd('Some debug Occured while launching at SJPANEL - STATUS CODE MISMATCH', $responseCode, $statusCode);
                return false;
            }
            $responseBody = $response->getBody();
            $responseContent = $responseBody->getContents();
            return json_decode($responseContent);
        }catch(ServerException $e){
            dd('ServerException, debug on launching in SJ Panel', $e);
        } catch (GuzzleException $e) {
            dd('GuzzleException', $e);
        }
    }

}
