<?php

namespace App\Http\Controllers\Web\Internal\Project;

use App\Http\Requests\Internal\Project\ReviewLaunch\LaunchProjectRequest;
use App\Models\Client\Client;
use App\Models\Project\Project;
use App\Models\Project\ProjectQuota;
use App\Models\Project\ProjectTopic;
use App\Models\Project\ProjectVendor;
use App\Models\Project\StudyType;
use App\Repositories\Internal\Project\ProjectRepository;
use App\Repositories\Internal\Project\ProjectStatusRepository;
use App\Repositories\Internal\Project\ProjectSurveyRepository;
use App\Repositories\Internal\Source\SourceRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class ProjectReviewLaunchController extends Controller
{
    public $projectRepository, $projectSurveyRepo, $sourceRepo, $projectStatusRepository;
    public function __construct(
        ProjectRepository $projectRepository,
        ProjectSurveyRepository $projectSurveyRepo,
        SourceRepository $sourceRepo,
        ProjectStatusRepository $projectStatusRepository)
    {
        $this->projectRepository = $projectRepository;
        $this->projectSurveyRepo = $projectSurveyRepo;
        $this->sourceRepo = $sourceRepo;
        $this->projectStatusRepository = $projectStatusRepository;
    }

    public function index(Request $request)
    {
        $id = $request->id;
        $project = Project::find($id);
        $study_type_id = $project->study_type_id;
        $study_type = StudyType::find($study_type_id);
        $quota = ProjectQuota::all()->where('project_id',$id);
        $project_vendors = ProjectVendor::with('source')->where('project_id',$id)->get();
        $project_topic = ProjectTopic::find($project->project_topic_id);
        $client = Client::find($project->client_id);

        $internalSource = $this->sourceRepo->getInternalSource();
        $internalVendor = $project_vendors->where('vendor_id', '=', $internalSource->id)->first();
        $internalTestSurvey = $this->projectSurveyRepo->getProjectVendorLink($project, $internalVendor);

        $testingData = false;
        if($request->has('test_id') ){
            $testingData = [
                'amrid' => $request->input('amrid'),
                'test_id' => $request->input('test_id')
            ];
        }

        return view('internal.project.edit.review_launch')
            ->with('project', $project)
            ->with('quotas',$quota)
            ->with('vendors',$project_vendors)
            ->with('study_type',$study_type)
            ->with('project_topic',$project_topic)
            ->with('clients',$client)
            ->with('testingData',$testingData)
            ->with('internalTestSurvey',$internalTestSurvey)
            ->with('internalSource',$internalSource);
    }

    public function launchProject(LaunchProjectRequest $request, $id)
    {
        $project = Project::find($id);
        if ( !$this->checkProjectCanBeLive() ) {
            dd('project cannot be live');
        }

        $nextStatusObject = $this->projectStatusRepository->getStatusByCode(config('app.project_statuses.pending.code', 'PENDING'));
        $currentStatusObject = $this->projectRepository->getProjectStatusById($project->status_id);

        $this->projectStatusRepository->prepareProjectForStatusChange($project, $currentStatusObject, $nextStatusObject);

        $project = $this->projectStatusRepository->changeProjectStatus($project, $nextStatusObject);
        if ( !$project ) {
            return Redirect::back()
                ->withErrors(['Error Occured']);
        }
        $this->projectStatusRepository->notifyForProjectStatusChanged($project, $currentStatusObject, $nextStatusObject);

        return redirect()->route('internal.project.index');

    }

    public function checkProjectCanBeLive()
    {
        /*$projectChecks = [];
        $projectChecks['basic'] = $this->checkProjectBasicDetails();
        $projectChecks['quota'] = $this->checkProjectQuotaDetails();
        $projectChecks['security_screener'] = $this->checkProjectSecurityScreenerDetails();
        $projectChecks['source_assignment'] = $this->checkProjectSourceQuotaAssignmentDetails();
        $projectChecks['panel_invite'] = $this->checkProjectPanelInviteDetails();*/
        return true;

    }

}
