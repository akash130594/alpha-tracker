<?php

namespace App\Listeners\Internal\Project\Status\Before;

use App\Events\Internal\Project\BeforeStatusChange;
use App\Repositories\Internal\Project\ProjectSurveyRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TBDListener
{
    public $statusCode;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->statusCode = config('app.project_statuses.tbd.code', 'TBD');
    }

    /**
     * Handle the event.
     *
     * @param  BeforeStatusChange  $event
     * @return void
     */
    public function handle(BeforeStatusChange $event)
    {
        $currentStatusObject = $event->currentStatus;
        if( empty($currentStatusObject) || $currentStatusObject->code !== $this->statusCode){
            return;
        }
        $project = $event->project;
        $nextStatusObject = $event->nextStatus;
        $projectSurveyRepo = new ProjectSurveyRepository();

        /*Create Initial Project Surveys when Moving to Pending Launch*/
        $projectVendors = $projectSurveyRepo->getProjectVendors($project->id);
        if (!empty($projectVendors)) {
            foreach ($projectVendors as $vendor) {
                if ( $vendor->surveys->isEmpty() ) {
                    $projectSurveyRepo->createSurveyForProjectVendor($project, $vendor);
                }
                continue;
            }
        }
        return;
    }
}
