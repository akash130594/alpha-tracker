<?php

namespace App\Models\Apace_temp;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Archive extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'archive_projects';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $dates = ['updated_at','created_at'];
    protected $fillable = [
        'project_id',
        'project_code',
        'client_code',
        'client_name',
        'client_var',
        'client_link',
        'client_project_no',
        'unique_id',
        'unique_id_file',
        'created_by',
        'loi',
        'cpi',
        'quota',
        'status',
        'name',
        'study_type',
        'country_id',
        'country_code',
        'created_date',
        'end_date',
        'loi_validation',
        'loi_validation_time',
        'redirector_flag',
        'project_vendors',
        'project_surveys',
        'traffics',
    ];
}
