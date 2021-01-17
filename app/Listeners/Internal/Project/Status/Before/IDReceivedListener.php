<?php

namespace App\Listeners\Internal\Project\Status\Before;

use App\Events\Internal\Project\BeforeStatusChange;
use App\Repositories\Internal\Project\ProjectStatusRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class IDReceivedListener
{
    public $statusCode;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ProjectStatusRepository $projectStatusRepo)
    {
        $this->statusCode = config('app.project_statuses.idreceived.code', 'IDRECVD');
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
        //DO Pending To Next Status Conversion

        /***IF NEXT STATUS IS BEING CHANGED TO INCENTIVE PAID AND BILLED THEN REQUEST IDS first***/
        $incentivePaidAndBilledCode = config('app.project_statuses.incentivepaid.code', 'IP');
        if ($nextStatusObject->code === $incentivePaidAndBilledCode) {

        }

        //Everything done;
        return;
    }
}
