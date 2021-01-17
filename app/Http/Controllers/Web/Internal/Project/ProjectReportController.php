<?php

namespace App\Http\Controllers\Web\Internal\Project;

use App\Models\Project\Project;
use App\Models\Project\ProjectQuota;
use App\Models\Project\ProjectVendor;
use App\Models\Traffics\Traffic;
use App\Repositories\Internal\Traffic\TrafficRepository;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ProjectReportController extends Controller
{
    public $trafficRepo;
    public function __construct(TrafficRepository $trafficRepo)
    {
        $this->trafficRepo = $trafficRepo;
        //$this->detailedProfileRepo = $detailedProfileRepo;
    }

    public function index(Request $request, $id)
    {
        $project = Project::find($id);

        $projectVendors = $this->getProjectVendorsSummary($project->id);
        $project->traffics = $this->trafficRepo->getStatsByProjectId($project->id);

        return view('internal.project.reports.traffic_summary')
            ->with('project', $project)
            ->with('project_vendors', $projectVendors);
    }

    public function quotaWiseSummary(Request $request, $id)
    {
        $project = Project::find($id);
        $quotaWithVendors = $this->getProjectQuotaWithVendors($project);
        $project->traffics = $this->trafficRepo->getStatsByProjectId($project->id);

        return view('internal.project.reports.quota_summary')
            ->with('project', $project)
            ->with('project_quota', $quotaWithVendors);
    }

    private function getProjectVendorsSummary($project_id, $quota_id = false)
    {
        $projectVendorsQuery = ProjectVendor::where('project_id', '=', $project_id)
            ->with('source');
        if ($quota_id) {
            $projectVendorsQuery
                ->whereRaw("find_in_set($quota_id,spec_quota_ids) > 0");
        }
        $projectVendors = $projectVendorsQuery->get();
        foreach ($projectVendors as $vendor) {
            $vendor->traffic = $this->trafficRepo->getStatsByProjectIdAndSourceId($project_id, $vendor->source->id);
        }
        return $projectVendors;
    }

    private function getProjectQuotaWithVendors($project)
    {
        $data = ProjectQuota::where('project_id', '=', $project->id)->get();
        $data->each(function ($quota, $key) use ($project) {
            $projectVendors = $this->getProjectVendorsSummary($project->id, $quota->id );
            //dd($projectVendors);
            $quota->vendors = $projectVendors;
            $quota->traffics = $this->trafficRepo->getStatsByProjectIdAndQuotaId($project->id, $quota->id);
        });
        return $data;
    }
}
