<?php

namespace App\Models\Source;

use Illuminate\Database\Eloquent\Model;

class SourceType extends Model
{
    protected $fillable = [
        'id',
        'code',
        'name',
        'additional_param'
    ];

    public $timestamps = false;
}
