<?php

namespace App\Library\Services\SourceAPI;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use function GuzzleHttp\Psr7\str;

class SourceAPIService implements SourceAPIServiceInterface
{
    public $url = 'https://sandbox.techops.engineering';
    public $apiKey = "188F4EEB-ED0A-49EA-B166-E97EA2383B5A";

    public function doSomethingUseful()
    {
        return 'Output from SourceAPIService';
    }

    public function getApiHeaders()
    {
        return [
            'headers' => [
                'User-Agent' => 'testing/1.0',
                'Accept'     => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => $this->apiKey,
            ]
        ];
    }

    public function createSurvey($project)
    {
        $actionUrl = "{{url}}/Demand/v1/Surveys/Create";
        $expectedStatusCode = 201;

        $creationData = [
            "AccountID" => 1,   //int   Unique account identifier.

            "ClientSurveyLiveURL" => "http://www.google.com",   //Our Live Survey URL
            "TestRedirectURL" => "http://www.google.com",   //string    Link to client survey for testing purposes.


            "IsFraudProfile" => false,  //true enables RelevantID Fraud Profile security
            "FraudProfileThreshold" => 11,   //int   Sets the RelevantID Fraud Profile Threshold between 0-100.
            "IsDedupe" => false,   //true enables Relevant ID dedupe security.
            "IsGeoIP" => false,   //true enables RelevantID GeoIP security to determine respondent geographical locatio
            "IsRelevantID" => false,  //true enables RelevantID security. RelevantID is a third-party security feature
            "IsTrueSample" => false,    // Boolean  Property is returned but is nonfunctional.

            "FulcrumExchangeAllocation" => 100,   //NEED TO CONFIRM   -   double  Percentage of total completes allocated only to Lucid Marketplace. Must be between 0 and 100%.
            "FulcrumExchangeHedgeAccess" => true,   //NEED TO CONFIRM   -   double  true gives the Marketplace access to any unallocated completes.
            "IsActive" => true,   //NEED TO CONFIRM   - Indicates if a survey is active or inactive in Marketplace database.

            "UniqueIPAddress" => true,     //boolean true enables IP deduplication on a survey preventing a respondent with the same IP address from entering more than once.
            "UniquePID" => true,        //boolean   true enables PID deduplication on a survey preventing a respondent with the same PID from entering more than once.
            "IsVerifyCallBack" => false,    //true enables Verify CallBack security which requires the correct [%RSFN%] variable to be included on the “complete” client callback for verification.

            "ClientCPI" => 999, //NEED TO CONFIRM - double Revenue per complete used to calculate internal margin or savings.
            "Quota" => 100,     //int   Total number of completes needed
            "QuotaCPI" => 1,  //double    Gross payout per complete. This value is before any applicable commissions or fees
            "QuotaCalculationTypeID" => 1,  //int     Sets the quota calculation method. Either 1 for ”Completes” (quotas counted using completes) or 2=”Prescreens”
            "BidIncidence" => 20,       //int   Estimated incidence rate for the survey.

            "SurveyPriority" => 11,       //int   Survey priority from 1-11 (1 being the highest).
            "SurveyName" => "Test Survey - ".time()."",     //string    External name of the survey. This name may be exposed to respondents.
            "CountryLanguageID" => 9,   //Add CountryLanguage ID For FL
            "IndustryID" => 1,    // int Industry associated with the survey’s topic.
            "StudyTypeID" => 1,     //int   Indicates the survey’s format and purpose (i.e. adhoc, recruit, etc).
            "SurveyStatusCode" => "01",     //string    Code associated with the current status of the survey
            "CollectsPII" => null,     //true indicates that the survey will collect PII
        ];

        $finalUrl = $this->applyUrlChange($actionUrl);
        $requestArray = $this->getApiHeaders();
        $requestArray['json'] = $creationData;

        try{
            $client = new GuzzleClient();

            $response = $client->post($finalUrl, $requestArray);

            $responseCode = $response->getStatusCode();

            if ($responseCode !== $expectedStatusCode) {
                dd('error Occured in response Code', $responseCode);
            }
            $responseBody = $response->getBody();
            $responseContent = $responseBody->getContents();

            dd($response, $responseBody, json_decode($responseContent));
        }catch(ServerException $e){
            dd('ServerException, error on launching in FL', $e);
        } catch (GuzzleException $e) {
            dd('GuzzleException', $e);
        }


        return 'Output from Create FulcrumAPIService';
    }

    public function updateSurvey()
    {
        /*Todo: Implement updateSurvey() method*/
    }

    private function getClient()
    {
        $client = new GuzzleClient([
            // Base URI is used with relative requests
            'base_uri' => $this->url,
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);
        return $client;
    }

    public function applyUrlChange($actionUrl)
    {
        $translations = [
            '{{url}}' => $this->url
        ];
        $translatedUrl = strtr($actionUrl, $translations);
        return $translatedUrl;

    }

}
