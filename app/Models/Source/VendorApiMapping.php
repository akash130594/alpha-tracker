<?php

namespace App\Models\Source;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class VendorApiMapping extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'vendor_api_mapping';

    protected $fillable = [
        '_id',
        'code',
        'name',
        'project_industry',
        'project_study_types',
        'project_statuses',
        'country_language',
    ];
}
