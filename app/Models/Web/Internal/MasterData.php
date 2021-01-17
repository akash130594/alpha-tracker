<?php

namespace App\Models\Web\Internal;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class MasterData extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'country_master_data';
    protected $fillable = [
        'country_code',
        'country_name',
        'country_data',
    ];

    public $timestamps = true;
}
