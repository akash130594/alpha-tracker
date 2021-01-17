<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;

class ProjectTopic extends Model
{
    /*TODO : Add Management Interface for Study Types*/
    /*While MIgration we have to take care of New IDS*/
    protected $fillable = [
        'id',
        'code',
        'name',
        'status',
        'order'
    ];

    public $timestamps = false;
    public static function laratablesCustomAction($survey_topic)
    {
        return view('internal.general.survey_topics.includes.index_action', compact('survey_topic'))
            ->render();
    }
}
