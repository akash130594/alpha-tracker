<?php

namespace App\Listeners\Internal\Project\Status\After;

use App\Events\Internal\Project\AfterStatusChanged;
use App\Events\Internal\Project\SourceAPI\SourceAPIEvents;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class HoldListener
{
    public $statusCode, $project;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->statusCode = config('app.project_statuses.hold.code', 'HOLD');
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
        event(SourceAPIEvents::PROJECT_PAUSED, new SourceAPIEvents($project));

        return;
    }
}
