<?php

namespace App\Models\Apace_temp;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class ProjectUniqueId extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'project_unique_id';
    protected $fillable = [
        'project_code',
        'project_id',
        'url_data',
    ];
}
