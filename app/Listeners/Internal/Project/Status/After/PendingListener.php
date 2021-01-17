<?php

namespace App\Listeners\Internal\Project\Status\After;

use App\Events\Internal\Project\AfterStatusChanged;
use App\Events\Internal\Project\SourceAPI\SourceAPIEvents;
use App\Library\Services\SourceAPI\SourceAPIServiceInterface;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PendingListener
{
    public $statusCode, $project, $sourceAPIService;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(SourceAPIServiceInterface $sourceAPIService)
    {
        $this->statusCode = config('app.project_statuses.pending.code', 'PENDING');
        $this->sourceAPIService = $sourceAPIService;
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
        event(SourceAPIEvents::PROJECT_CREATED, new SourceAPIEvents($project));

        return;
    }
}
