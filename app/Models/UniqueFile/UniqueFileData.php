<?php

namespace App\Models\UniqueFile;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class UniqueFileData extends Eloquent
{

    protected $connection = 'mongodb';
    protected $collection = 'project_unique_ids';

    protected $fillable = [
        'project_code',
        'project_id',
        'url_data',
        'updated_at',
        'created_at',
    ];
}
