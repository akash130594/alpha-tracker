<?php

namespace App\Listeners\Internal\Project\Status\After;

use App\Events\Internal\Project\AfterStatusChanged;
use App\Models\Project\Project;
use App\Repositories\Internal\Archive\ArchivesRepository;
use App\Repositories\Internal\General\GeneralRepository;
use App\Repositories\Internal\Project\ProjectRepository;
use App\Repositories\Internal\Project\ProjectStatusRepository;
use App\Repositories\Internal\Traffic\TrafficRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ArchivedListener
{
    public $statusCode, $project;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public $project_repo, $survey_repo, $trafficRepo,$arch_repo, $project_status_repo;
    public function __construct(ProjectRepository $project_repository,
                                ArchivesRepository $archRepo)
    {
        $this->statusCode = config('app.project_statuses.archived.code', 'ARCH');
        $this->project_repo = $project_repository;
        $this->arch_repo = $archRepo;
    }

    /**
     * Handle the event.
     *
     * @param  AfterStatusChanged  $event
     * @return void
     */
    public function handle(AfterStatusChanged $event)
    {
        $currentStatusObject = $event->currentStatusObject;
        if( empty($currentStatusObject) || $currentStatusObject->code !== $this->statusCode){
            return;
        }
        $project = $event->project;
        $previousStatusObject = $event->previousStatusObject;
        $this->getArchiveData($project->id);
    }

    private function getArchiveData($project_id)
    {
        $archive = [];
        $project_details = Project::select('*')->where('id',$project_id)->with('client','user')->get();
        foreach($project_details as $project){
            $archive_stats = [];
            $archive_stats['id'] = $project->id;
            $archive_stats['project_id'] = $project->id;
            $archive_stats['code'] = $project->code;
            $archive_stats['client_code'] = $project->client_code;
            $archive_stats['client_name'] = $project->client->name;
            $archive_stats['client_var'] = $project->client_var;
            $archive_stats['client_link'] = $project->client_link;
            $archive_stats['client_project_no'] = $project->client_project_no;
            $archive_stats['unique_id'] = $project->unique_id;
            $archive_stats['unique_ids_file'] = $project->unique_ids_file;
            $archive_stats['unique_ids_flag'] = $project->unique_ids_flag;
            $archive_stats['can_links'] = $project->can_links;
            $archive_stats['created_by'] = $project->user->first_name." ".$project->user->last_name;
            $archive_stats['loi'] = $project->loi;
            $archive_stats['cpi'] = $project->cpi;
            $archive_stats['ir'] = $project->ir;
            $archive_stats['incentive'] = $project->incentive;
            $archive_stats['quota'] = $project->quota;
            $archive_stats['name'] = $project->name;
            $archive_stats['label'] = $project->name;
            $archive_stats['study_type'] = $project->study_type_id;
            $archive_stats['country_id'] = $project->country_id;
            $archive_stats['country_code'] = $project->country_code;
            $archive_stats['language_id'] = $project->language_id;
            $archive_stats['language_code'] = $project->language_code;
            $archive_stats['start_date'] = date_format($project->start_date,"Y-m-d H:i:s");
            $archive_stats['end_date'] = date_format($project->end_date,"Y-m-d H:i:s");
            $archive_stats['loi_validation'] = $project->loi_validation;
            $archive_stats['loi_validation_time'] = $project->loi_validation_time;
            $archive_stats['redirector_flag'] = $project->redirector_flag;
            $archive_stats['project_vendors'] = $this->getProjectVendors($project_id);
            $archive_stats['project_surveys'] = $this->getProjectSurveys($project_id);
            $archive_stats['project_dedupe'] = $this->getProjectDedupe($project->survey_dedupe_list_id);
            $archive_stats['survey_dedupe_flag'] = $project->survey_dedupe_flag;
            $archive_stats['survey_dedupe_list_id'] = $project->survey_dedupe_list_id;
            $archive_stats['project_custom_screener'] = $this->getProjectCustomScreener($project_id);
            $archive_stats['project_quota'] = $this->getProjectQuota($project_id);
            $archive_stats['traffics'] = $this->project_repo->getProjectTraffics($project_id);
            $archive = $archive_stats;
        }
        $insert_archive = $this->arch_repo->createArchive($archive);
        if($insert_archive){
            return true;
        }else{
            return false;
        }
    }
    private function getProjectVendors($project_id)
    {
        $vendors = [];
        $project_vendors = $this->project_repo->getProjectVendors($project_id);
        return $project_vendors;
    }

    private function getProjectSurveys($project_id)
    {
        $project_surveys = $this->project_repo->getProjectSurveys($project_id);
        return $project_surveys;
    }
    private function getProjectDedupe($dedupe_list_id)
    {
        $project_dedupe = $this->project_repo->getProjectDedupe($dedupe_list_id);
        return $project_dedupe;
    }
    private function getProjectCustomScreener($project_id)
    {
        $project_screener = $this->project_repo->getProjectCustomScreener($project_id);
        return $project_screener;
    }
    private function getProjectQuota($project_id)
    {
        $project_quota = $this->project_repo->getProjectQuotaDetails($project_id);
        return $project_quota;
    }
}
