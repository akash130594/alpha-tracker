<?php

namespace App\Models\MasterQuestion;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class ProfileQuestions extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'profile_question_master';
    protected $fillable = [
        'id',
        'q_id',
        'general_name',
        'display_name',
        'country_code',
        'order',
        'type',
        'show_as',
        'profile_section_id',
        'translated',
        'updated_at',
        'created_at',
    ];

    public static function findById($question_id)
    {
        return self::where('id', $question_id)->first();
    }
}
