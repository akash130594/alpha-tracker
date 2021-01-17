<?php

namespace App\Models\MasterQuestion;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class GlobalQuestion extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'global_question_master';

    protected $fillable = [
        'id',
        'general_name',
        'display_name',
        'type',
        'show_as',
        'profile_section_id',
        'order',
        'translated',
        'updated_at',
        'created_at',
    ];

    public static function findById($question_id)
    {
        return self::where('id', $question_id)->first();
    }
}
