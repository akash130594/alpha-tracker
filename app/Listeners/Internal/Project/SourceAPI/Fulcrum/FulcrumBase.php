<?php

namespace App\Listeners\Internal\Project\SourceAPI\Fulcrum;


use App\Listeners\Internal\Project\SourceAPI\Fulcrum\traits\methods\FulcrumMethods;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\GuzzleException;

class FulcrumBase
{
    use FulcrumMethods;

    public $apaceSourceCode, $url, $apiKey, $accountId;

    public $survey_number;

    public function __construct()
    {
        $this->url = config('settings.FL_API.API_URL');
        $this->apiKey = config('settings.FL_API.API_KEY');
        $this->accountId = config('settings.FL_API.ACCOUNT_ID');
        $this->apaceSourceCode = config('settings.FL_API.SOURCE_CODE');
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
            dd('GuzzleException', $e);
        }
    }

    public function getProjectInfo($surveyNumber)
    {
        $actionUrl = '{{url}}/Demand/v1/Surveys/BySurveyNumber/{{SurveyNumber}}';
        $statusCode = 200;
        $this->survey_number = $surveyNumber;
        $finalUrl = $this->applyUrlChange($actionUrl);
        $requestArray = $this->getApiHeaders();
        try{
            $client = new Client();

            $response = $client->get($finalUrl, $requestArray);

            $responseCode = $response->getStatusCode();

            if ($responseCode !== $statusCode) {
                return false;
            }
            $responseBody = $response->getBody();
            $responseContent = $responseBody->getContents();
            return json_decode($responseContent, true)['Survey'];
        }catch(ServerException $e){
            dd('ServerException, error on launching in FL', $e);
        } catch (GuzzleException $e) {
            dd('GuzzleException', $e);
        }
    }
}
