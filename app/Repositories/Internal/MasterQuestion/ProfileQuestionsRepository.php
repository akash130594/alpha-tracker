<?php
/*******************************************************************************
 * Copyright (c) 2019. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
 * Morbi non lorem porttitor neque feugiat blandit. Ut vitae ipsum eget quam lacinia accumsan.
 * Etiam sed turpis ac ipsum condimentum fringilla. Maecenas magna.
 * Proin dapibus sapien vel ante. Aliquam erat volutpat. Pellentesque sagittis ligula eget metus.
 * Vestibulum commodo. Ut rhoncus gravida arcu.
 ******************************************************************************/

namespace App\Repositories\Internal\MasterQuestion;

use App\Models\MasterQuestion\GlobalQuestion;
use App\Models\MasterQuestion\ProfileQuestions;
use App\Repositories\BaseMongoRepository;

class ProfileQuestionsRepository extends BaseMongoRepository
{
    private $collection = 'profile_question_master';

    private function getProfileQuestionCollection()
    {
        return $this->getCollection( $this->collection );
    }

    public function getProfileQuestions()
    {
        $data = $this->getProfileQuestionCollection()->get();
        dd($data);
    }

    public function getProfileQuestionsByLocale($country_code, $language_code)
    {
        //dd($country_code ."-". $language_code);

        $projectArray =
            [
                '_id' => 0,
                'id' => 1,
                'profile_section_id' => true,
                'profile_section_code' => true,
                'profile_section' => true,
                'general_name' => 1,
                'display_name' => true,
                'type' => true,
                'show_as' => true,
                'order' => true,
                'translated' => [
                    '$elemMatch' => ['con_lang' => "$country_code-$language_code"]
                    //'$elemMatch' => ['con_lang' => "FR-FR"]
                ],
            ];

        $profileQuestions = ProfileQuestions::where('country_code', '=', $country_code)
            ->whereRaw( [ 'translated' => ['$elemMatch' => ['con_lang' => "$country_code-$language_code"]] ] )
            ->project($projectArray)->get();

        /*$profileQuestions = $data->filter(function($item) {
            return isset($item->translated);
        });*/

        return $profileQuestions;
    }

    public function getProfileQuestionsByLocaleAndQuestions($country_code, $language_code, $question_ids)
    {
        //dd($country_code ."-". $language_code);

        $projectArray =
            [
                '_id' => 0,
                'id' => 1,
                'profile_section_id' => true,
                'profile_section_code' => true,
                'profile_section' => true,
                'general_name' => 1,
                'display_name' => true,
                'type' => true,
                'show_as' => true,
                'order' => true,
                'translated' => [
                    '$elemMatch' => ['con_lang' => "$country_code-$language_code"]
                    //'$elemMatch' => ['con_lang' => "FR-FR"]
                ],
            ];
        $profileQuestions = ProfileQuestions::whereIn('id', $question_ids)
            ->where('country_code', '=', $country_code)
            ->whereRaw( [ 'translated' => ['$elemMatch' => ['con_lang' => "$country_code-$language_code"]] ] )
            ->project($projectArray)
            ->get();

        /*$profileQuestions = $data->filter(function($item) {
            return isset($item->translated);
        });*/

        return $profileQuestions;
    }

    public function getProfileQuestionByCode($general_name, $country_code, $language_code)
    {
        $projectArray =
            [
                '_id' => 0,
                'id' => 1,
                'profile_section_id' => true,
                'profile_section' => true,
                'general_name' => 1,
                'display_name' => true,
                'type' => true,
                'show_as' => true,
                'order' => true,
                'translated' => [
                    '$elemMatch' => ['con_lang' => "$country_code-$language_code"]
                    //'$elemMatch' => ['con_lang' => "FR-FR"]
                ],
            ];

        $profileQuestions = ProfileQuestions::where('general_name', '=', $general_name)
            ->whereRaw( [ 'translated' => ['$elemMatch' => ['con_lang' => "$country_code-$language_code"]] ] )
            ->project($projectArray)->first();

        return $profileQuestions;
    }

    public function getProfileQuestionsIdsByLocale($country_code, $language_code)
    {
        $projectArray =
            [
                'general_name' => 1,
                'translated' => [
                    '$elemMatch' => ['con_lang' => "$country_code-$language_code"]
                    //'$elemMatch' => ['con_lang' => "FR-FR"]
                ],
            ];
        $profileQuestions = ProfileQuestions::where('country_code', '=', $country_code)
            ->whereRaw( [ 'translated' => ['$elemMatch' => ['con_lang' => "$country_code-$language_code"]] ] )
            ->project($projectArray)->get()->toArray();

        foreach($profileQuestions as $question){
            $data[] = $question['general_name'];
        }
        return $data;
    }

}
