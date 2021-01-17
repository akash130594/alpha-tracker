<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;

class ProjectDedupe extends Model
{
    protected $fillable = [
        'id',
        'dedupe_status',
        'dedupe_data',
        'dedupe_selected_filter'
    ];

    public $timestamps = false;
}
