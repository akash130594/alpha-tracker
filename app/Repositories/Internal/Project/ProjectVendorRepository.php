<?php
/**
 * Created by PhpStorm.
 * User: SampleJunction
 * Date: 05-12-2018
 * Time: 11:11 PM
 */

namespace App\Repositories\Internal\Project;

use App\Models\Project\Project;
use App\Models\Project\ProjectQuota;
use App\Models\Project\ProjectQuotaSpec;
use App\Models\Project\ProjectSurvey;
use App\Models\Project\ProjectVendor;
use App\Models\Sjpanel\ProfileQuestion;
use App\Models\Source\Source;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;


class ProjectVendorRepository extends BaseRepository
{
    public function model()
    {
        return Project::class;
    }

    public function getVendorDetail($project_vendors_id)
    {
        $data = Source::whereIn('id',$project_vendors_id)->get();
        return $data;
    }
    public function getVendor($vendor_id)
    {
        $data = ProjectVendor::where('id','=',$vendor_id)->with('source')->first();
        return $data;
    }
    public function getQuota($id)
    {
        $data = ProjectQuota::select('name','id')->where('project_id',$id)->get();
        return $data;
    }
    public function getQuotaSelectionDetail($quota_selection_id)
    {
        $data = ProjectQuota::select('id','name')->whereIn('id',$quota_selection_id)->get();
        return $data;
    }
    public function updateVendor($update_data,$project_vendor_id )
    {
        $data = ProjectVendor::where('id','=',$project_vendor_id )->update($update_data);
        return $data;
    }
    public function getSurveyExclLinks($id,$vendor_id)
    {
        $data = ProjectSurvey::where([
            ['project_vendor_id',$vendor_id],
            ['project_id',$id],
        ])->first();
        return $data;
    }
    public function getProjectVendors($project_id)
    {
        $projectVendors = ProjectVendor::where('project_id', '=', $project_id)->with('source')->get();
        return $projectVendors;


        /*
            $data =DB::table('project_vendors')
                ->join('project_surveys','project_vendors.id','=','project_surveys.project_vendor_id')
                ->join('sources','project_vendors.vendor_id','=','sources.id')
                ->select('project_vendors.id as project_vendor_id','project_vendors.vendor_screener_excl_flag as vendor_screener_excl_flag','project_vendors.cpi as cpi','project_vendors.quota as quota','project_vendors.global_screener as vendor_global_screener','project_vendors.predefined_screener as vendor_predefined_screener','project_vendors.custom_screener as vendor_custom_screener','project_vendors.spec_quota_ids','project_vendors.spec_quota_names','project_vendors.vendor_id as vendor_id','sources.name as name','sources.*','is_active',DB::raw("count(project_surveys.project_vendor_id)as count"))
                ->where('project_vendors.project_id','=',$project_id)
                ->groupBy('project_surveys.project_vendor_id')
                ->get();
       return $data->toArray();*/
    }

    public function getCurrentVendor($project_id)
    {
        $data = ProjectVendor::select('vendor_id')->with('source')->where('project_id',$project_id)->get();
       return $data;
    }

    public function getVendorRemain($vendor_id,$vendor_name)
    {
        $data = Source::whereNotIn('id',$vendor_id)->get();
       return $data;
    }
    public function getSourceDetails($vendor_id)
    {
        $data = Source::select('code')->where('id',$vendor_id)->first();
        return $data;
    }
    public function getProjectQuota($project_id)
    {
        $data = ProjectQuota::where('project_id',$project_id)->get();
        return $data;
    }
    public function getQuotaName($quota_ids)
    {
        $data = ProjectQuota::select('name')->whereIn('id',$quota_ids)->get();
        return $data->toArray();
    }
    public function addVendor($add_data)
    {
        $vendor = ProjectVendor::create($add_data);
        return $vendor;
    }
    public function getProject($project_id)
    {
        $data = Project::where('id',$project_id)->first();
        return $data;
    }
    public function getTotalVendor()
    {
        $data = Source::all();
        return $data;
    }

    public function getAssignedQuotaByVendorCode($project_id, $vendor_code)
    {
        $quotaIds = ProjectVendor::where('vendor_code', '=', $vendor_code)
            ->where('project_id', '=', $project_id)
            ->select(['spec_quota_ids'])
            ->first();

        $explodedIds = explode(',', $quotaIds->spec_quota_ids);
        $quotas = ProjectQuota::whereIn('id', $explodedIds)->get();
        return $quotas;
    }
}
