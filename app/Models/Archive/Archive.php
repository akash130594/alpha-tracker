<?php

namespace App\Models\Archive;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use App\Models\Project\Project;

class Archive extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'archive_projects';

    protected $fillable = [
        'id',
        'project_id',
        'code',
        'client_code',
        'client_name',
        'client_var',
        'client_link',
        'client_project_no',
        'unique_id',
        'unique_ids_file',
        'unique_ids_flag',
        'can_links',
        'created_by',
        'loi',
        'cpi',
        'ir',
        'incentive',
        'quota',
        'name',
        'label',
        'study_type',
        'country_id',
        'country_code',
        'language_id',
        'language_code',
        'start_date',
        'end_date',
        'loi_validation',
        'loi_validation_time',
        'redirector_flag',
        'project_vendors',
        'project_surveys',
        'project_dedupe',
        'survey_dedupe_flag',
        'survey_dedupe_list_id',
        'project_custom_screener',
        'project_quota',
        'traffics'
    ];
    protected $dates = ['started_at', 'ended_at', 'created_at', 'updated_at' ];
}
