<?php
namespace App\Listeners\Internal\Project\SourceAPI\Fulcrum\Project;


use App\Events\Internal\Project\SourceAPI\SourceAPIEvents;
use App\Listeners\Internal\Project\SourceAPI\Fulcrum\FulcrumBase;
use App\Listeners\Internal\Project\SourceAPI\Fulcrum\traits\methods\FulcrumQualificationMethods;
use App\Listeners\Internal\Project\SourceAPI\Fulcrum\traits\methods\FulcrumQuotaMethods;
use App\Models\Source\VendorApiMapping;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Session;
use App\Repositories\Internal\Project\ProjectSurveyRepository;
use Carbon\Carbon;

class ProjectCreate extends FulcrumBase //implements ShouldQueue
{
    use FulcrumQuotaMethods, FulcrumQualificationMethods;
    public $project;
    public function createProject(SourceAPIEvents $sourceAPIEvent)
    {
        $actionUrl = "{{url}}/Demand/v1/Surveys/Create";
        $expectedStatusCode = 201;

        $project = $this->project = $sourceAPIEvent->project;
        $projectVendor = $project->hasSource($this->apaceSourceCode);

        /*If Project Don't have FL Vendor then SKIP*/
        if ( !$projectVendor ) {
            return;
        }
        $projectSurveyRepo = new ProjectSurveyRepository();
        $project_surveys = $projectSurveyRepo->getProjectVendorSurvey([$projectVendor->id]);
        if (empty($project_surveys)) {
            return;
        }

        $vendorMapping = VendorApiMapping::where('code', '=', $this->apaceSourceCode)->first();

        $mappedIndustryIDs = array_column($vendorMapping->project_industry, 'SOURCE_MAP_ID', 'SJ_ID');
        $mappedSurveyTypeIDs = array_column($vendorMapping->project_study_types, 'SOURCE_MAP_ID', 'SJ_ID');
        $country_langs = collect($vendorMapping->country_language);
        $language_item = $country_langs
            ->where('SJ_CON_CODE', '=', $project->country_code)
            ->where('SJ_LANG_CODE', '=', $project->language_code)
            ->first();

        $mappedProjectStatusCodes = array_column($vendorMapping->project_statuses, 'SOURCE_MAP_CODE', 'SJ_ID');

        foreach ($project_surveys as $survey) {
            $creationData = [
                "ClientSurveyLiveURL" => $survey->generateSurveyLiveLink(),   //Our Live Survey URL
                "TestRedirectURL" => $survey->generateSurveyTestLink(),   //string    Link to client survey for testing purposes.

                "SurveyName" => $project->code."-".$survey->code." - ".Carbon::now()->toDateTimeString(),     //string    External name of the survey. This name may be exposed to respondents.
                "CountryLanguageID" => (!empty($language_item))?$language_item['FL_CON_LANG_ID']:9,   //Add CountryLanguage ID For FL
                "IndustryID" => $mappedIndustryIDs[$project->project_topic_id],    // int Industry associated with the survey’s topic.
                "StudyTypeID" => $mappedSurveyTypeIDs[$project->study_type_id],     //int   Indicates the survey’s format and purpose (i.e. adhoc, recruit, etc).
                "SurveyStatusCode" => $mappedProjectStatusCodes[$project->status_id],     //string    Code associated with the current status of the survey
                "CollectsPII" => ($project->collects_pii)?true:null,     //true indicates that the survey will collect PII

                "ClientCPI" => 999, //NEED TO CONFIRM - double Revenue per complete used to calculate internal margin or savings.
                "Quota" => $project->quota,     //int   Total number of completes needed
                "QuotaCPI" => 1,  //double    Gross payout per complete. This value is before any applicable commissions or fees

                "BidIncidence" => (int) $project->ir,       //int   Estimated incidence rate for the survey.
            ];
            $finalData = array_merge($this->projectCreateData(), $creationData);

            $callApiData = [
                'action_url' => $actionUrl,
                'expected_status_code' => $expectedStatusCode,
                'post_data' => $finalData,
            ];

            $response = $this->callFulcrumAPI($callApiData);

            if($response){
                $survey->vendor_survey_code = $this->survey_number = $response->Survey->SurveyNumber;
                $survey->survey_live_url = $response->Survey->ClientSurveyLiveURL;
                $survey->survey_test_url = $response->Survey->TestRedirectURL;
                $survey->status_id = $project->status_id;
                $survey->status_label = $project->status_label;
                $survey->save();

                $this->createQualification();
                $this->createQuota();

                Session::flash('flash_success', 'FL Launched #'.$response->Survey->SurveyNumber);
            }else{
                Session::flash('flash_danger', 'FL Launch Error Occured');
            }
        }
        return;
    }

    public function projectCreateData()
    {
        $data = [
            "AccountID" => $this->accountId,   //int   Unique account identifier.

            "IsFraudProfile" => false,  //true enables RelevantID Fraud Profile security
            "FraudProfileThreshold" => 11,   //int   Sets the RelevantID Fraud Profile Threshold between 0-100.
            "IsDedupe" => false,   //true enables Relevant ID dedupe security.
            "IsGeoIP" => false,   //true enables RelevantID GeoIP security to determine respondent geographical locatio
            "IsRelevantID" => false,  //true enables RelevantID security. RelevantID is a third-party security feature
            "IsTrueSample" => false,    // Boolean  Property is returned but is nonfunctional.

            "FulcrumExchangeAllocation" => 0,   //NEED TO CONFIRM   -   double  Percentage of total completes allocated only to Lucid Marketplace. Must be between 0 and 100%.
            "FulcrumExchangeHedgeAccess" => true,   //NEED TO CONFIRM   -   double  true gives the Marketplace access to any unallocated completes.
            "IsActive" => true,   //NEED TO CONFIRM   - Indicates if a survey is active or inactive in Marketplace database.

            "UniqueIPAddress" => true,     //boolean true enables IP deduplication on a survey preventing a respondent with the same IP address from entering more than once.
            "UniquePID" => true,        //boolean   true enables PID deduplication on a survey preventing a respondent with the same PID from entering more than once.
            "IsVerifyCallBack" => false,    //true enables Verify CallBack security which requires the correct [%RSFN%] variable to be included on the “complete” client callback for verification.

            "SurveyPriority" => 1,       //int   Survey priority from 1-11 (1 being the highest).
            "QuotaCalculationTypeID" => 1,  //int     Sets the quota calculation method. Either 1 for ”Completes” (quotas counted using completes) or 2=”Prescreens”
        ];

        return $data;
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

            $response = $client->post($finalUrl, $requestArray);

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
            dd('GuzzleException', $e, $e->getTraceAsString(), $e->getTraceAsString(), $requestArray);
        }
    }
}
