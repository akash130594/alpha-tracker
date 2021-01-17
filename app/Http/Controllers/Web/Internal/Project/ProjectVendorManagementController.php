<?php

namespace App\Http\Controllers\Web\Internal\Project;

use App\Models\Project\Project;
use App\Repositories\Internal\Project\ProjectSurveyRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Internal\Project\ProjectVendorRepository;
use App\Models\Project\ProjectQuota;
use App\Models\Project\ProjectStatus;
use App\Models\Project\ProjectVendor;
use App\Models\Source\Source;
use App\Models\Project\StudyType;
use App\Models\Project\ProjectTopic;
use App\Models\General\Country;
use App\Models\General\Language;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Internal\Project\Vendor\AddVendorRequest;
use App\Http\Requests\Internal\Project\Vendor\VendorManagementRequest;

class ProjectVendorManagementController extends Controller
{
    public $vendor_repo;
    public function __construct(ProjectVendorRepository $vendorRepo)
    {
        $this->vendor_repo = $vendorRepo;
    }
    public function index(Request $request)
    {
        $project_id = $request->id;
        $project_vendors = $this->vendor_repo->getProjectVendors($project_id);
        return view('internal.project.vendor.index')
            ->with('project_vendors',$project_vendors)
            ->with('project_id',$project_id);
    }
/********************************Tested By AS*************************************************************/
    public function editVendor(Request $request)
    {
        $id = $request->id;
        $vendor_id = $request->vendor_id;
        $project_vendors = $this->vendor_repo->getVendor($vendor_id);
        $specs_quota_id = explode(',',$project_vendors->spec_quota_ids);
        $quota_details = $this->vendor_repo->getQuota($id);
        $get_surveys_excl_links = $this->vendor_repo->getSurveyExclLinks($id,$vendor_id);
        $quota = setting('project_quota');
        return view('internal.project.vendor.edit')
            ->with('vendor_id',$vendor_id)
            ->with('project_id',$id)
            ->with('quota_details',$quota_details)
            ->with('get_surveys_excl_links',$get_surveys_excl_links)
            ->with('project_vendors',$project_vendors)
            ->with('quota',$quota);
    }
    public function editPostVendor(VendorManagementRequest $request)
    {
        $quota = $request->input('quota',false);
        $global_screener = $request->input('global_screener',false);
        $predefined_screener = $request->input('defined_screener',false);
        $custom_screener = $request->input('custom_screener',false);
        $quota_selection_id = $request->input('quota_selection',false);
        $status = $request->input('status',false);
        $link = $request->input('link',false);
        $complete = $request->input('complete',false);
        $terminate = $request->input('terminate',false);
        $quota_full = $request->input('quota_full',false);
        $quality_term = $request->input('quality_term',false);
        $quota_name = [];
        if($quota_selection_id){
            $quota_selection_name = $this->vendor_repo->getQuotaSelectionDetail($quota_selection_id);
            foreach ($quota_selection_name as $quota){
                $quota_name[] = $quota->name;
            }
            $quota_data = [
                'spec_quota_names' => implode(',',$quota_name),
                'spec_quota_ids' => implode(',',$quota_selection_id),
            ];
        }else{
            $quota_data = [];
        }
        $project_vendor_id = $request->vendor_id;
        if($global_screener || $custom_screener || $predefined_screener ){
            $vendor_screener_excl_flag = 1;
        } else{
            $vendor_screener_excl_flag = 0;
        }
        $data = [
            'global_screener' => $global_screener,
            'predefined_screener' => $predefined_screener,
            'custom_screener' => $custom_screener,
            'vendor_screener_excl_flag' => $vendor_screener_excl_flag,
            'quota' => $quota,
            'is_active' => $status,
            'sy_excl_link_flag' => $link,
            'syv_complete' => $complete,
            'syv_terminate' => $terminate,
            'syv_quotafull' => $quota_full,
            'syv_qualityterm' => $quality_term,
        ];
        $update_data = array_merge($data,$quota_data);
        $update_vendor = $this->vendor_repo->updateVendor($update_data,$project_vendor_id );
        if($update_vendor==true){
            return Redirect::back()->withFlashSuccess("Update has been successfully done");
        }else{
            return Redirect::back()->withError("Some Error Occurred");
        }
    }
    /******************************Tested By AS***********************************************************/
    public function addVendor(Request $request)
    {
        $project_id = $request->id;
        $get_current_project_vendor = $this->vendor_repo->getCurrentVendor($project_id);
        $get_project_quota = $this->vendor_repo->getProjectQuota($project_id);
        $project = $this->vendor_repo->getProject($project_id);
        if(!$get_current_project_vendor->isEmpty()){
            foreach ($get_current_project_vendor as $vendor){
                $vendor_id[] = $vendor['vendor_id'];
                $vendor_name[] = $vendor['source']['name'];
            }
            $vendor_remains = $this->vendor_repo->getVendorRemain($vendor_id,$vendor_name);
        } else {
            $vendor_remains = collect();
        }
        $total_vendors = $this->vendor_repo->getTotalVendor();
        $quota = setting('project_quota');
        return view('internal.project.vendor.add')
            ->with('vendor_remains',$vendor_remains)
            ->with('get_project_quota',$get_project_quota)
            ->with('project_id',$project_id)
            ->with('project',$project)
            ->with('quota',$quota)
            ->with('total_vendors',$total_vendors);
    }

    public function vendorDetails(Request $request)
    {
        $vendor_id = $request->input('vendor_id',false);
        $get_vendor_details = $this->vendor_repo->getSourceDetails($vendor_id);
        if($get_vendor_details) {
            return response()->json($get_vendor_details, 200);
        }
        return response()->json([])->setStatusCode(404);
    }
    public function postVendor(AddVendorRequest $request)
    {
        $vendor_screener_excl_flag = 0;
        $project_id = $request->id;
        $project = Project::find($project_id);
        $input = $request->except(['_token','spec_quota_ids']);
        if( array_key_exists('global_screener',$input) || array_key_exists('predefined_screener',$input) || array_key_exists('custom_screener',$input) ){
            $vendor_screener_excl_flag = 1;
        }
        $quota_ids  = $request->input('spec_quota_ids',false);
        if($quota_ids){
            $spec_quota_ids = implode(",",$quota_ids);
            $get_quota_names = $this->vendor_repo->getQuotaName($quota_ids);
            foreach($get_quota_names as $quota_name){
                $name[] = $quota_name['name'];
            }
            $name = implode(",",$name);
        } else {
            $spec_quota_ids = null;
            $name = null;
        }
        $data2 = [
            'spec_quota_ids' => $spec_quota_ids,
            'spec_quota_names' => $name,
            'project_id' => $project_id,
            'vendor_screener_excl_flag' => $vendor_screener_excl_flag,
        ];
        $add_data = array_merge($input,$data2);
        $newVendor = $this->vendor_repo->addVendor($add_data);
        if( $newVendor ){
            /*Create Vendor Survey*/
            $projectSurveyRepo = new ProjectSurveyRepository();
            $projectSurveyRepo->createSurveyForProjectVendor($project, $newVendor);
            return Redirect::back()
                ->withFlashSuccess("Vendor Has Been Added");
        }else{
            return Redirect::back()
                ->withErrors("Vendor Cannot be ADDED");
        }
    }
}
