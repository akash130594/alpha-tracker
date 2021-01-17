<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;

class ProjectStatus extends Model
{
    /*TODO : Add Management Interface for Statuses*/
    /*While MIgration we have to take care of New IDS*/
    protected $fillable = [
        'id',
        'code',
        'name',
        'next_status_flow',
        'status',
        'order',
    ];

    public $timestamps = false;
}
