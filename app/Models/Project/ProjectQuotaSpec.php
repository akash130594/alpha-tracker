<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;

class ProjectQuotaSpec extends Model
{
    protected $fillable = [
        'id',
        'project_quota_id',
        'is_global',
        'question_general_name',
        'question_id',
        'type',
        'values',
        'raw_spec',
    ];

    public $timestamps = false;
}
