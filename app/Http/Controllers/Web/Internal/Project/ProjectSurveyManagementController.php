<?php

namespace App\Http\Controllers\Web\Internal\Project;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use  App\Repositories\Internal\Project\ProjectSurveyRepository;
use App\Models\Project;
use App\Models\Project\ProjectQuota;
use App\Models\Project\ProjectStatus;
use App\Models\Project\ProjectVendor;
use App\Models\Source\Source;
use App\Models\Project\StudyType;
use App\Models\Project\ProjectTopic;
use App\Models\General\Country;
use App\Models\General\Language;
use  App\Models\Project\ProjectSurvey;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Internal\Survey\SurveyRequest;
use Illuminate\Support\Str;

class ProjectSurveyManagementController extends Controller
{
    public $survey_repo;
    public function __construct(ProjectSurveyRepository $surveyRepo)
    {
        $this->survey_repo = $surveyRepo;
    }
    public function index(Request $request)
    {
        $project_vendor_id = $request->vendor_id;
        $project_id = $request->id;
        $project_surveys = $this->survey_repo->getProjectSurveys($project_vendor_id);
        $project_status = $this->survey_repo->getStatus();
      foreach ($project_surveys as $source_name){
          $name = $source_name['source']['name'];
      }
        return view('internal.project.surveys.index')
            ->with('project_surveys',$project_surveys)
            ->with('vendor_name',$name)
            ->with('project_vendor_id',$project_vendor_id)
            ->with('project_id',$project_id)
            ->with('project_statuses',$project_status);
    }

    /***************************Tested By AS*************************************************************************/
    public function createSurveys(Request $request)
    {
       $project_vendor_id = $request->vendor_id;
       $project_id = $request->id;
       $get_surveys_details = $this->survey_repo->getSurveyDetail($project_vendor_id)->toArray();
        $project_vendor = $this->survey_repo->getVendorDetails($project_vendor_id);
        $tbd_id = $this->survey_repo->getTBDId();
        $vendor_code = $project_vendor->vendor_code;
        $vendor_id = $project_vendor->vendor_id;
        $project_code = $project_vendor->project_code;
        $status_id = $tbd_id->id;
       return view('internal.project.surveys.create')
           ->with('project_vendor_id',$project_vendor_id)
           ->with('project_id',$project_id)
           ->with('get_surveys_details',$get_surveys_details)
           ->with('vendor_code',$vendor_code)
           ->with('vendor_id',$vendor_id)
           ->with('project_code',$project_code)
           ->with('status_id',$status_id);
    }


    private function generateSurveyCode()
    {
        $code = str_random('4');
        return $code;
    }
    public function postSurveys(SurveyRequest $request)
    {
        $data = $request->except(['_token','creation_type','status_id']);
        $status_id = $request->input('status_id',false);
        $get_status_name = $this->survey_repo->getStatusName($status_id);
        $project_vendor_id = $request->vendor_id;
        $project_id = $request->id;
        if($request->input('creation_type',false)=="automatic"){
         $vendor_survey_code = Str::random(10);
        } else {
          $vendor_survey_code = $request->input('vendor_survey_code',false);
         }
        $data1 = [
            'vendor_survey_code' => $vendor_survey_code,
        ];
        $code = $this->generateSurveyCode();
        $get_vendor_id = $this->survey_repo->getVendorId($project_vendor_id);
        $data2 = [
            'code' => $code,
            'vendor_id' => $get_vendor_id->vendor_id,
            'project_vendor_id' => $project_vendor_id,
            'project_id' => $project_id,
            'status_id' => $status_id,
            'status_label' => $get_status_name['name'],
        ];
        $get_excl_link_info = $this->survey_repo->getExclInfo($project_vendor_id);
        $post_surveys = $this->survey_repo->createSurveys($data,$data1,$data2,$get_vendor_id);
        if($post_surveys){
            return redirect()->route('internal.project.vendors.details',[$project_id,$project_vendor_id])
                ->withFlashSuccess("New Survey Created");
        }else{
            return redirect()->route('internal.project.vendors.details',[$project_id,$project_vendor_id])
                ->withError("some error occurred");
        }
    }
    public function getVendorStatusFlow(Request $request)
    {
        $vendor_id = $request->input('vendor_id',false);
        $status_id = $request->input('status_id',false);
        $get_status_flow = ProjectStatus::where('id', '=', $status_id)->first();
        if ($get_status_flow) {
            $flow_status = explode(',',$get_status_flow->next_status_flow);
            $get_status = ProjectStatus::whereIn('code',$flow_status)->pluck('name', 'id')->toArray();
            return response()->json($get_status, 200);
        }
        return response()->json([])->setStatusCode(404);
    }
    public function postStatus(Request $request)
    {
        $id_not_updated = [];
        $id_updated = [];
        $update_status = false;
        $selected_status = $request->input('status',false);
        $current_status = $request->input('current_status',false);
        $selected_survey_id = $request->input('selected_id',false);
        for($i=0;$i<count($selected_survey_id);$i++){
            $status_flow = $this->survey_repo->getStatusFlow($current_status[$i]);
            $selected_status_name = $this->survey_repo->getSelectedStatusName($selected_status);
            $status_name = strtoupper($selected_status_name->name);
            $get_next_flow = explode(",",$status_flow->next_status_flow);
            if(in_array($status_name,$get_next_flow)) {
                $id_updated[] = $selected_survey_id[$i];
                $data = [
                    'status_id' => $selected_status,
                    'status_label' => $selected_status_name->name,
                ];
                $update_status = $this->survey_repo->updateStatus($selected_survey_id[$i],$data);
            }else{
                $id_not_updated[] = $selected_survey_id[$i];
            }
        }
        if($update_status){
            return Redirect::back()
                ->withFlashSuccess(count($id_updated)." Surveys Has Been updated & ".count($id_not_updated)." Surveys Cannot be Updated");
        } elseif($update_status && (count($id_not_updated)==0)){
            return Redirect::back()
                ->withFlashSuccess(count($id_updated)." Surveys Has Been updated");
        }else{
            return Redirect::back()
                ->withErrors(count($id_not_updated)." Surveys cannot be Updated.");
        }
    }

    public function postModalSurveys(Request $request)
    {
       $survey_status = $request->input('survey_status',false);
       $survey_id = $request->input('vendor_id',false);
       $selected_status_name = $this->survey_repo->getSelectedStatusName($survey_status);
       $data = [
           'status_id' => $survey_status,
           'status_label' => $selected_status_name->name,
       ];
       $update_status = $this->survey_repo->updateModalStatus($survey_id,$data);
      if($update_status){
          return Redirect::back()
              ->withFlashSuccess("Status Updated");
      } else {
          return Redirect::back()
              ->withErrors("some error occurred");
      }
    }
    public function viewLinks(Request $request)
    {
        $project_id = $request->input('project_id');
        $project_vendors = $this->survey_repo->getProjectVendors($project_id);
        return view('internal.project.surveys.view_link')
            ->with('project_vendors',$project_vendors);
    }
}
