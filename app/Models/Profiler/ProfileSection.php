<?php

namespace App\Models\Profiler;

use App\Models\Profiler\Traits\Scope\ProfileSectionScope;
use App\Models\Profiler\Traits\Method\ProfileSectionMethods;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class ProfileSection extends Eloquent
{

    protected $connection = 'mongodb';
    protected $collection = 'profile_sections_master';

    use ProfileSectionScope, ProfileSectionMethods;

    protected $fillable = [
        'general_name',
        'display_name',
        'type',
        'completion_time',
        'points',
        'status',
        'order',
        'translated',
    ];

    public $timestamps = true;
}
