<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;

class ProjectCustomScreener extends Model
{
    protected $fillable = [
        'id',
        'project_id',
        'screener_json',
    ];

    public $timestamps = false;
}
