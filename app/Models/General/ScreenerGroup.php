<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class ScreenerGroup extends Model
{
    protected $fillable = [
        'id',
        'code',
        'name',
        'additional_param',
    ];

    public $timestamps = false;

}
