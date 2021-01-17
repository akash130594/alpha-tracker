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
use App\Models\Project\ProjectStatus;
use App\Models\Project\ProjectSurvey;
use App\Models\Project\ProjectVendor;
use App\Models\Sjpanel\ProfileQuestion;
use App\Models\Source\Source;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use Illuminate\Support\Str;


class ProjectSurveyRepository extends BaseRepository
{
    public function model()
    {
        return Project::class;
    }

    public function getProjectSurveys($project_vendor_id)
    {
        $data = ProjectSurvey::where('project_vendor_id',$project_vendor_id)->with('source')->get()->toArray();
        return $data;
    }
    public function getSurveyDetail($project_vendor_id)
    {
        $data = ProjectSurvey::where('project_vendor_id',$project_vendor_id)
            ->with('status')
            ->get();
        return $data;
    }
    public function getExclInfo($project_vendor_id)
    {
        $data = ProjectVendor::where('id',$project_vendor_id)->first();
        return $data;
    }

    public function getStatusName($status_id)
    {
        $data = ProjectStatus::select('name')->where('id',$status_id)->first();
        return $data->toArray();
    }

    public function getVendorId($project_vendor_id)
    {
       $data = ProjectVendor::select('vendor_id')->where('id','=',$project_vendor_id)->first();
        return $data;
    }
    public function createSurveys($data,$data1,$data2,$vendor_id)
    {
        if($data['collection_dedupe']==1){
            $data['collection_ids']= implode(",",$data['collection_ids']);
        }
        $new_data = array_merge($data,$data1,$data2);
        $insert = ProjectSurvey::create($new_data);
        return $insert;
    }
    public function updateProjectVendorStatus($project_vendor_id,$data)
    {
        $update = ProjectSurvey::where('project_vendor_id',$project_vendor_id)->update($data);
        return $update;
    }
    public function getStatus()
    {
        $data = ProjectStatus::all()->toArray();
        return $data;
    }
    public function updateStatus($selected_survey_id,$data)
    {
        $data = ProjectSurvey::where('id',$selected_survey_id)->update($data);
        return $data;
    }
    public function getStatusFlow($current_status)
    {
        $data = ProjectStatus::select('next_status_flow')->where('id',$current_status)->first();
       return $data;
    }
    public function getSelectedStatusName($selected_status)
    {
        $data = ProjectStatus::select('name')->where('id',$selected_status)->first();
        return $data;
    }
    public function updateModalStatus($survey_id,$data)
    {
        $data = ProjectSurvey::where('id',$survey_id)->update($data);
        return $data;
    }
    public function getSurveyData($data)
    {
        $collection_id = implode(",",$data['collection_ids']);
        $new_data =array_replace($data['collection_ids'],$collection_id);

    }
    public function getProjectVendors($project_id)
    {
        $data = ProjectVendor::where('project_id',$project_id)
            ->with('source','surveys')
            ->get();
        return $data;
    }

    public function getProjectVendorByProjectId($project_id, $sourceCode)
    {
        $data = ProjectVendor::where('project_id',$project_id)
            ->where('vendor_code', '=', $sourceCode)
            ->first();

        return $data;
    }

    public function generateProjectSurveyCode()
    {
        return Str::random(6);
    }

    public function createSurveyForProjectVendor($project, $projectVendor)
    {

        $data = [
            'code' => $this->generateProjectSurveyCode(),
            'project_vendor_id' => $projectVendor->id,
            'project_id' => $project->id,
            'project_code' => $project->code,
            'vendor_id' => $projectVendor->vendor_id,
            'vendor_code' => $projectVendor->vendor_code,
            'vendor_survey_code' => Str::random(10),
            'sy_excl_link_flag' => false,
            'collection_dedupe' => false,
            'status_id' => 1,
            'status_label' => 'TBD',
        ];

        $whereCond = [
            'project_id' => $data['project_id'],
            'vendor_id' => $data['vendor_id'],
        ];

        ProjectSurvey::updateOrCreate($whereCond, $data);
    }

    public function getProjectVendorSurvey($project_vendor_id)
    {
        $data = ProjectSurvey::whereIn('project_vendor_id',$project_vendor_id)->get();
       return $data;
    }

    public function getProjectVendorLink($project_id, $projectVendor)
    {
        $projectSurvey = ProjectSurvey::where('project_id', '=', $project_id->id)
                        ->where('project_vendor_id', '=', $projectVendor->id)
                        ->first();

        return $projectSurvey;
    }
    public function getVendorDetails($project_vendor_id)
    {
        $project_vendor_data = ProjectVendor::where('id','=',$project_vendor_id)
            ->first();
        return $project_vendor_data;
    }

    public function getTBDId()
    {
        $id = ProjectStatus::select('id')->where('code', '=', 'TBD')->first();
        return $id;
    }
}
