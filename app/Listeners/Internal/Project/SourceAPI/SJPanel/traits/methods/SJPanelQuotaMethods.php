<?php

namespace App\Listeners\Internal\Project\SourceAPI\SJPanel\traits\methods;


use App\Repositories\Internal\Project\ProjectVendorRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\GuzzleException;

trait SJPanelQuotaMethods
{
    private function createQuotaQualifications()
    {
        //Get Assigned Quota
        $projectVendorRepo = new ProjectVendorRepository();
        $quotas = $projectVendorRepo->getAssignedQuotaByVendorCode($this->project->id, 'SJPL');
        foreach ($quotas as $quota) {
            $quotaMap = [];
            $quotaMap['Name'] = $quota->name;
            $quotaMap['Quota'] = $quota->count;
            $quotaMap['IsActive'] = ($quota->status)?true:false;
            $formattedQuotaSpec = json_decode($quota->formatted_quota_spec);
            $quotaMap['Conditions'] = $this->SJPanelQualification($this->project, $formattedQuotaSpec);
            $this->createProjectQuota($quotaMap);
        }
    }

    private function createProjectQuota($quotaData)
    {
        $actionUrl = "{{url}}/api/project/add-quota/{{SurveyNumber}}";
        $statusCode = 200;
        $postData = $quotaData;

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
            dd('ServerException, debug on launching in SJ Panel', $e, $e->getTraceAsString(), $e->getTraceAsString(), $requestArray);
        } catch (GuzzleException $e) {
            dd('GuzzleException', $e, $e->getTraceAsString(), $e->getTraceAsString(), $requestArray);
        }
    }

    private function SJPanelQualification($project, $qualifications)
    {
        $qualsMaps = [];
        foreach ($qualifications as $qualificationType => $qualificationItems) {
            if ($qualificationType == 'global') {
                $qualsMaps = array_merge($qualsMaps, $this->mapSJPanelQuestions($project, $qualificationItems));
            }
            if ($qualificationType == 'detailed') {
                $qualsMaps = array_merge($qualsMaps, $this->mapSJPanelQuestions($project, $qualificationItems));
            }
        }
        return $qualsMaps;
    }

    private function mapSJPanelQuestions($project, $qualificationItems)
    {
        $questionCodes = array_keys((array)$qualificationItems);
        $questionMapping = [];
        foreach ($qualificationItems as $question_name => $precodes) {
            $questionMapped['QuestionID'] = $question_name;
            /*Changed Global age range to specific values instead if range*/
            if ($question_name == 'GLOBAL_AGE') {
                $ageRanges = [];
                foreach ($precodes as $range) {
                    $arrRange = explode('-', $range[0]);
                    $ageRange = range($arrRange[0], $arrRange[1]);
                    $ageRanges = array_merge($ageRanges, $ageRange);
                }
                $questionMapped['PreCodes'] = $ageRanges;
            } else if ($question_name == 'GLOBAL_ZIP') {
                /*Hotfix for Global Zip as there was additional character status in precodes*/
                if (!empty($precodes) && ( $key = array_search('status', $precodes[0])) !== false) {
                    unset($precodes[$key]);
                }
                $zipcodes = reset($precodes);
                if (isset($zipcodes->values)) {
                    $zipcodes = explode(PHP_EOL, $zipcodes->values);
                }
                $questionMapped['PreCodes'] = $zipcodes;
            } else{
                $selectedAnswersArrr = $qualificationItems->{$question_name};
                $selAnswers = [];
                foreach ($selectedAnswersArrr as $answer) {
                    $selAnswers[] = $answer[0];
                }
                $mappedPrecodes = $selAnswers;
                $questionMapped['PreCodes'] = $mappedPrecodes;
            }
            $questionMapping[] = $questionMapped;
        }
        return $questionMapping;
    }
}
