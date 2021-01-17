<?php

namespace App\Listeners\Internal\Project\SourceAPI\Fulcrum\traits\methods;


use App\Repositories\Internal\MasterQuestion\GlobalQuestionsRepository;
use App\Repositories\Internal\MasterQuestion\ProfileQuestionsRepository;
use App\Repositories\Internal\Project\ProjectQuotaRepository;
use App\Repositories\Internal\Project\ProjectVendorRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\GuzzleException;

trait FulcrumQualificationMethods
{
    public function createQualification($project = null)
    {
        if (!empty($project)) {
            $this->project = $project;
        }
        //Get Assigned Quota
        $projectVendorRepo = new ProjectVendorRepository();
        $quotas = $projectVendorRepo->getAssignedQuotaByVendorCode($this->project->id, '1185');
        $qualifications = [];
        foreach ($quotas as $quota) {
            $formattedQuotaSpec = json_decode($quota->formatted_quota_spec);
            $qualifications = array_merge($qualifications, $this->mapFulcrumQualification($this->project, $formattedQuotaSpec));
        }

        $existingQualifications = $this->listProjectQualifications()->Qualifications;
        $matchElements = [];
        if ( !empty($existingQualifications) ) {
            $existingQualificationsArray = [];
            foreach ($existingQualifications as $qualification) {
                $qualification = (array)$qualification;
                $question_id = $qualification['QuestionID'];
                $element = array_search($question_id, array_column($qualifications, 'QuestionID'));
                if ($element !== false) {
                    $matchElements[] = $element;
                    $item = $qualifications[$element];
                    $existingQualificationsArray[] = $item;
                }
            }
            foreach ($existingQualificationsArray as $changedQualification) {
                $this->updateProjectQualification($changedQualification);
            }

        }
        if (!empty($matchElements)) {
            $qualifications = array_diff_key($qualifications, array_flip($matchElements));
        }

        foreach ($qualifications as $qualification) {
            $this->createProjectQualification($qualification);
        }
    }

    public function createProjectQualification($qualification)
    {
        $actionUrl = "{{url}}/Demand/v1/SurveyQualifications/Create/{{SurveyNumber}}";
        $statusCode = 201;
        $postData = $qualification;

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

    public function updateProjectQualification($qualification)
    {
        $actionUrl = "{{url}}/Demand/v1/SurveyQualifications/Update/{{SurveyNumber}}";
        $statusCode = 200;
        $postData = $qualification;

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
            dd('GuzzleException', $e, $e->getTraceAsString(), $e->getTraceAsString(), $requestArray);
        }
    }

    public function listProjectQualifications()
    {
        $actionUrl = "{{url}}/Demand/v1/SurveyQualifications/BySurveyNumber/{{SurveyNumber}}";
        $statusCode = 200;

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

            //dd($response, $responseBody, json_decode($responseContent));
            return json_decode($responseContent);
        }catch(ServerException $e){
            dd('ServerException, error on launching in FL', $e);
        } catch (GuzzleException $e) {
            dd('GuzzleException', $e, $e->getTraceAsString(), $e->getTraceAsString(), $requestArray);
        }

    }

    private function mapFulcrumQualification($project, $qualifications)
    {
        $qualsMaps = [];
        foreach ($qualifications as $qualificationType => $qualificationItems) {
            if ($qualificationType == 'global') {
                $qualsMaps = array_merge($qualsMaps, $this->importGlobalFLMap($project, $qualificationItems));
            }
            if ($qualificationType == 'detailed') {
                $qualsMaps = array_merge($qualsMaps, $this->importProfileMap($project, $qualificationItems));
            }
        }
        return $qualsMaps;
    }

    private function importProfileMap($project, $qualificationItems)
    {
        $profileQuestionsRepo = new ProfileQuestionsRepository();
        $questionCodes = array_keys( (array) $qualificationItems );
        $questions = $profileQuestionsRepo->getProfileQuestionsByLocaleAndQuestions($project->country_code, $project->language_code, $questionCodes);
        $questionMapping = [];
        foreach ($questions as $question) {
            $questionMapped = [];
            if ( !empty($question->translated) && isset($question->translated[0])) {
                $mapData = $question->translated[0];
                if (empty($mapData['mapping']) && empty($mapData['mapping']['FL'])) {
                    dd('FL not Exist');
                }
                $question_data = $mapData['mapping']['FL']['fulcrum_question_id'];
                $questionMapped['QuestionID'] = $question_data;
                $questionMapped['Name'] = $question->general_name;
                $questionMapped['QuestionID'] = $question_data;
                $questionMapped['LogicalOperator'] = "OR";
                $questionMapped['NumberOfRequiredConditions'] = 1;
                $questionMapped['IsActive'] = true;

                $selectedAnswersArrr = $qualificationItems->{$question->general_name};
                if (!empty($mapData['answers'])) {
                    $answers_data = collect($mapData['answers']);
                    $selAnswers = [];
                    foreach ($selectedAnswersArrr as $answer) {
                        $selAnswers[] = $answer[0];
                    }
                    $mappedPrecodes = $answers_data->whereIn('precode', $selAnswers)->pluck('mapping.FL.precode')->toArray();
                    $questionMapped['PreCodes'] = $mappedPrecodes;
                }
            }
            $questionMapping[] = $questionMapped;
        }
        return $questionMapping;
    }

    private function importGlobalFLMap($project, $qualificationItems)
    {
        $globalQuestionRepo = new GlobalQuestionsRepository();
        $questionCodes = array_keys((array)$qualificationItems);
        $questions = $globalQuestionRepo->getGlobalQuestionsByLocale($project->country_code, $project->language_code, $questionCodes);
        $questionMapping = [];
        foreach ($questions as $question) {
            $questionMapped = [];
            if ( !empty($question->translated) && isset($question->translated[0])) {
                $mapData = $question->translated[0];
                if (empty($mapData['mapping']) && empty($mapData['mapping']['FL'])) {
                    dd('FL not Exist');
                }
                $question_data = $mapData['mapping']['FL']['fulcrum_question_id'];
                $questionMapped['Name'] = $question->general_name;
                $questionMapped['QuestionID'] = $question_data;
                $questionMapped['LogicalOperator'] = "OR";
                $questionMapped['NumberOfRequiredConditions'] = 1;
                $questionMapped['IsActive'] = true;

                $selectedAnswersArrr = $qualificationItems->{$question->general_name};
                if ( !empty($mapData['answers']) ) {
                    $answers_data = collect($mapData['answers']);
                    $selAnswers = [];
                    foreach ($selectedAnswersArrr as $answer) {
                        $selAnswers[] = $answer[0];
                    }
                    $mappedPrecodes = $answers_data->whereIn('precode', $selAnswers)->pluck('mapping.FL.precode')->toArray();
                    $questionMapped['PreCodes'] = $mappedPrecodes;
                } else {
                    if ($question->general_name == 'GLOBAL_AGE') {
                        $ageRanges = [];
                        foreach ($selectedAnswersArrr as $range) {
                            $arrRange = explode('-', $range[0]);
                            $ageRange = range($arrRange[0], $arrRange[1]);
                            $ageRanges = array_merge($ageRanges, $ageRange);
                        }
                        $questionMapped['PreCodes'] = $ageRanges;
                    } else if ($question->general_name == 'GLOBAL_ZIP') {
                        if (!empty($selectedAnswersArrr) && ( $key = array_search('status', $selectedAnswersArrr[0])) !== false) {
                            unset($selectedAnswersArrr[$key]);
                        }
                        $zipcodes = reset($selectedAnswersArrr);
                        if (isset($zipcodes->values)) {
                            $zipcodes = explode(PHP_EOL, $zipcodes->values);
                        }
                        $questionMapped['PreCodes'] = $zipcodes;
                    }
                }
            }
            $questionMapping[] = $questionMapped;
        }
        return $questionMapping;
    }
}
