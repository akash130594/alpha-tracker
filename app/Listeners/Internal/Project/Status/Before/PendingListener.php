<?php

namespace App\Listeners\Internal\Project\Status\Before;

use App\Events\Internal\Project\BeforeStatusChange;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PendingListener
{
    public $statusCode;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->statusCode = config('app.project_statuses.pending.code', 'PENDING');
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


        //Everything done;
        return;
    }
}
