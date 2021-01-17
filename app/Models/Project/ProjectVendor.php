<?php

namespace App\Models\Project;

use App\Models\Source\Source;
use App\Models\Traffics\Traffic;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project\Traits\ProjectVendorUserStamp;
use Jenssegers\Mongodb\Eloquent\HybridRelations;

class ProjectVendor extends Model
{

    use HybridRelations;
    use ProjectVendorUserStamp;
    /*TODO : Add Management Interface for Project Vendors*/
    protected $fillable = [
        'id',
        'project_id',
        'project_code',
        'vendor_id',
        'vendor_code',
        'spec_quota_ids',
        'spec_quota_names',
        'cpi',
        'quota',
        'sy_excl_link_flag',
        'syv_complete',
        'syv_terminate',
        'syv_quotafull',
        'syv_qualityterm',
        'quota_completes',
        'quota_remains',
        'vendor_screener_excl_flag',
        'global_screener',
        'predefined_screener',
        'custom_screener',
        'is_active',
    ];


    public $timestamps = true;

    public function source()
    {
        return $this->hasOne(Source::class, 'id', 'vendor_id');
    }
    public function surveys()
    {
        return $this->hasMany(ProjectSurvey::class,'project_vendor_id','id');
    }
    public function quota()
    {
        return $this->hasMany(ProjectQuota::class,'project_id','project_id');
    }

    public static function laratablesCustomScreener($vendors)
    {
        $global =
        $result = '';
        $result .= ( !empty($vendors->global_screener) )?'<span class="material-icons">done</span>':'<span class="material-icons">cancel</span>';
        $result.='&nbsp;&nbsp;';

        $result .= ( !empty($vendors->defined_screener) )?'<span class="material-icons">done</span>':'<span class="material-icons">cancel</span>';
        $result.='&nbsp;&nbsp;';
        $result .= ( !empty($vendors->custom_screener) )?'<span class="material-icons">done</span>':'<span class="material-icons">cancel</span>';
        return $result;
    }

    public function traffics()
    {
        return $this->hasMany( Traffic::class, 'project_vendor_id');
    }

    public function trafficStarts()
    {
        $instance = $this->hasMany( Traffic::class, 'project_vendor_id')
            ->groupBy('project_vendor_id');
        //$instance->getQuery()->aggregate();
        dd($instance->get());
        return $instance->get();

        /*return
            ->where('project_vendor_id, count(*) as count')
            ->groupBy('project_vendor_id');*/
    }

    public function trafficCompletes()
    {
        return $this->hasMany( Traffic::class, 'project_vendor_id');
    }

    public function trafficTerminates()
    {
        return $this->hasMany( Traffic::class, 'project_vendor_id');
    }

    public function trafficAbandons()
    {
        return $this->hasMany( Traffic::class, 'project_vendor_id');
    }

}
