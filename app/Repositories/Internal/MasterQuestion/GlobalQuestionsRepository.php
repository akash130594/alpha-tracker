<?php

namespace App\Repositories\Internal\MasterQuestion;

use App\Models\MasterQuestion\GlobalQuestion;
use App\Repositories\BaseMongoRepository;

class GlobalQuestionsRepository extends BaseMongoRepository
{
    private $collection = 'global_question_master';

    private function getGlobalQuestionCollection()
    {
        return $this->getCollection( $this->collection );
    }

    public function getGlobalQuestions()
    {
        $data = $this->getGlobalQuestionCollection()->get();
        dd($data);
    }

    public function getGlobalQuestionLangs($question_id)
    {
        $projectArray =
            [
                '_id' => 0,
                'id' => 1,
                'translated.con_lang' => true
            ];


        $data = GlobalQuestion::where('id', '=', $question_id)
            ->project($projectArray)->first();
        return $data;
    }

    public function getGlobalQuestionIds()
    {
        $data = [
            'GLOBAL_ZIP',
            'GLOBAL_AGE',
            'GLOBAL_GENDER',
            'GLOBAL_EDUCATION',
            'GLOBAL_ETHNICITY',
            'GLOBAL_INCOME',
        ];
        return $data;
    }

    public function getGlobalQuestionsByLocale($country_code, $language_code, $question_ids = null)
    {
        if(empty($question_ids))
            $question_ids = $this->getGlobalQuestionIds();

        $projectArray =
            [
                '_id' => 0,
                'id' => 1,
                'general_name' => 1,
                'display_name' => true,
                'type' => true,
                'show_as' => true,
                'translated' => [
                    '$elemMatch' => ['con_lang' => "$country_code-$language_code"]
                    //'$elemMatch' => ['con_lang' => "FR-FR"]
                ],
            ];
        $data = GlobalQuestion::whereIn('id', $question_ids)
            ->whereRaw( [ 'translated' => ['$elemMatch' => ['con_lang' => "$country_code-$language_code"]] ] )
            ->project($projectArray)->get();
        return $data;
    }
}
