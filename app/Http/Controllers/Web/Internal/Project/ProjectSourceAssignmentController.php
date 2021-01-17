<?php

namespace App\Http\Controllers\Web\Internal\Project;

use App\Models\Project\Project;
use App\Models\Project\ProjectQuota;
use App\Models\Project\ProjectVendor;
use App\Models\Project\StudyType;
use App\Models\Source\Source;
use App\Repositories\Internal\Project\ProjectRepository;
use App\Repositories\Internal\Project\ProjectSurveyRepository;
use App\Repositories\Internal\Source\SourceRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Internal\Project\SourceQuotaAssignmentProjectRequest;

class ProjectSourceAssignmentController extends Controller
{
    public $projectSurveyRepo, $sourceRepo, $projectRepo;
    public function __construct(ProjectRepository $projectRepo, ProjectSurveyRepository $projectSurveyRepo, SourceRepository $sourceRepo)
    {
        $this->projectRepo = $projectRepo;
        $this->projectSurveyRepo = $projectSurveyRepo;
        $this->sourceRepo = $sourceRepo;
        //$this->detailedProfileRepo = $detailedProfileRepo;
    }

    public function editProjectSourcesQuota(Request $request, $id)
    {
        $project = Project::find($id);

        /*Todo: Remove Internal Source from below Vendors to Ensure, Project Survey Creation*/

        $project_vendors = ProjectVendor::where('project_id', '=', $project->id)->get();
        $study_types = StudyType::all()->pluck('name', 'id')->toArray();
        $sources = Source::where('status', '=', 1)->get();
        $project_quota = ProjectQuota::all()->where('project_id','=',$id)->pluck('name','id')->toArray();
        $quota_id = [];
        $quota_name = [];
        if($project_quota){
            foreach ($project_quota as $key=>$value){
                $quota_id[] = $key;
                $quota_name[] = $value;
            }
        }
        $all_quota_id = implode(",",$quota_id);
        $all_quota_name = implode(",",$quota_name);
        return view('internal.project.edit.sources_quota')
            ->with('project', $project)
            ->with('sources', $sources)
            ->with('project_vendors', $project_vendors)
            ->with('study_types', $study_types)
            ->with('project_quotas',$project_quota)
            ->with('quota_id',$all_quota_id)
            ->with('quota_name',$all_quota_name);
    }

    public function updateProjectSourcesQuota(SourceQuotaAssignmentProjectRequest $request, $id)
    {
        $project = Project::find($id);
        $source_ids =  $request->input('source_id');
        $cpi = $request->input('cpi');
        $quota = $request->input('quota');
        $screener = $request->input('screener');
        $quota_assign = $request->input('quota_assign');
        $source_array = [];
        $all_quota = ProjectQuota::where('project_id',$id)->get();
        foreach ($source_ids as $source_id) {
            $source_array[$source_id] = [
                'cpi' => $cpi[$source_id],
                'quota' => $quota[$source_id],
                'screener' => $screener[$source_id],
                'quota_selected' => $quota_assign[$source_id],
            ];
        }
        $quota_ids = [];
        $quota_name = [];
        foreach ($all_quota as $get_quota){
            $quota_ids[] = $get_quota->id;
            $quota_name[] = $get_quota->name;
        }
        $quota_ids = implode(",",$quota_ids);
        $quota_name = implode(",",$quota_name);
        ProjectVendor::where('project_id', '=', $project->id)->delete();
        foreach ($source_array as $vendor_id => $vendor_items) {
            $vendor_code = Source::select('code')->where('id','=',$vendor_id)->first();
            $screener_data = [];
            if($vendor_items['screener']){
                foreach ($vendor_items['screener'] as $screener=>$value){
                    $screener_data[$screener] = $value;
                    $screener_data['vendor_screener_excl_flag'] = 1;
                }
            }
            $quota_data = $this->getQuotaInfo($project->id, $vendor_items['quota_selected']);
            $createData = [
                'project_id' => $project->id,
                'project_code' => $project->code,
                'vendor_id' => $vendor_id,
                'vendor_code' => $vendor_code->code,
                'spec_quota_ids' => $quota_data['ids'],
                'spec_quota_names' => $quota_data['names'],
                'cpi' => $vendor_items['cpi'],
                'quota' => $vendor_items['quota'],
            ];
            $data = array_merge($createData,$screener_data);
            /*Todo: if Vendor Code is Internal then Skip Saving it*/

            ProjectVendor::create($data);

        }
        $this->createInternalSourceSurvey($project);

        return redirect()->back()->withFlashSuccess('Project Updated');
        /*Todo Save this Data in Project Vendors*/
    }


    /*Todo: This function is now moved to createProject in Project Controller*/
    private function createInternalSourceSurvey($project)
    {
        $internalSource = $this->sourceRepo->getInternalSource();
        $internalProjectVendor = $this->projectSurveyRepo->getProjectVendorByProjectId($project->id, $internalSource->code);
        if ( empty($internalProjectVendor) ) {

            $all_quotas = $this->projectRepo->getProjectQuota($project->id)->pluck('name', 'id');
            $createData = [
                'project_id' => $project->id,
                'project_code' => $project->code,
                'vendor_id' => $internalSource->id,
                'vendor_code' => $internalSource->code,
                'spec_quota_ids' => $all_quotas->keys()->implode(','),
                'spec_quota_names' => $all_quotas->implode(','),
                'cpi' => $project->cpi,
                'quota' => $project->quota,
            ];
            $internalProjectVendor = ProjectVendor::create($createData);
        }

        $this->projectSurveyRepo->createSurveyForProjectVendor($project, $internalProjectVendor);
    }

    private function getQuotaInfo($project_id, $quota_ids)
    {
        $responseData = ['ids' => '', 'names' => ''];
        if(empty($quota_ids)){
            $get_all_quota_per_project = ProjectQuota::where('project_id',$project_id)->pluck('id')->toArray();
            $get_id = implode(",",$get_all_quota_per_project);
            $get_all_quota_name = ProjectQuota::select('name')->whereIn('id',$get_all_quota_per_project)->pluck('name')->toArray();
            $responseData['ids'] = $get_id;
            $responseData['names'] = implode(', ', $get_all_quota_name);
        }else{
            $quota_ids = explode(',', $quota_ids);
            $quotaData = ProjectQuota::whereIn('id', $quota_ids)->where('project_id', '=', $project_id)->select(['id', 'name'])->get();
            if (!empty($quotaData)) {
                $plucked_ids = $quotaData->pluck('id')->toArray();
                $plucked_names = $quotaData->pluck('name')->toArray();
                $responseData['ids'] = implode(',', $plucked_ids);
                $responseData['names'] = implode(', ', $plucked_names);
            }
        }
        return $responseData;
    }
}
