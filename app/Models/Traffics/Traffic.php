<?php

namespace App\Models\Traffics;

use App\Models\Project\ProjectVendor;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use App\Models\Project\Project;
class Traffic extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'traffics';

    protected $fillable = [
        'cid',
        'mode',
        'linkid',
        'project_vendor_id',
        'survey_id',
        'survey_code',
        'project_id',
        'project_code',
        'project_name',
        'study_type_id',
        'study_type_name',
        'project_topic_id',
        'project_topic_name',
        'client_id',
        'client_code',
        'client_name',
        'client_var',
        'country_id',
        'country_code',
        'country_name',
        'language_id',
        'language_code',
        'language_name',
        'source_type_id',
        'source_type_name',
        'source_id',
        'source_name',
        'vvars',
        'status',
        'status_name',
        'resp_status',
        'resp_status_name',
        'vendorsourceurl',
        'clientsourceurl',
        'started_at',
        'duration',
        'ended_at',
        'starttime',
        'filled_screeners',
    ];

    public function projectVendor()
    {
        return $this->belongsTo(ProjectVendor::class, 'project_vendor_id', 'id');
    }
}
