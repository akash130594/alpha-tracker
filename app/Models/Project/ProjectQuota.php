<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;

class ProjectQuota extends Model
{
    protected $fillable = [
        'id',
        'project_id',
        'name',
        'description',
        'cpi',
        'count',
        'raw_quota_spec',
        'formatted_quota_spec',
        'type',
        'status',
    ];

    public $timestamps = false;

    /*public function projectVendors()
    {
        $data = $this->hasMany(ProjectVendor::class, 'profile_section_id', 'id')
            ->select([
                'id',
                'profile_section_id',
                'general_name',
                'display_name',
                'type',
            ])
            ->where([
                'visibility' => 'public',
                'status' => 'active',
            ])
        ;
        return $this->hasMany(ProjectVendor::class,'project_vendor_id','id');
    }*/
}
