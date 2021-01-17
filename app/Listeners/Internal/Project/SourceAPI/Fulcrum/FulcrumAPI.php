<?php

namespace App\Listeners\Internal\Project\SourceAPI\Fulcrum;


use App\Events\Internal\Project\SourceAPI\SourceAPIEvents;
use App\Listeners\Internal\Project\SourceAPI\Fulcrum\traits\methods\FulcrumMethods;
use App\Models\Source\VendorApiMapping;
use App\Repositories\Internal\Project\ProjectStatusRepository;
use App\Repositories\Internal\Project\ProjectSurveyRepository;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Queue\ShouldQueue;

class FulcrumAPI
{

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            SourceAPIEvents::PROJECT_CREATED,
            'App\Listeners\Internal\Project\SourceAPI\Fulcrum\Project\ProjectCreate@createProject'
        );

        $events->listen(
            SourceAPIEvents::PROJECT_LAUNCHED,
            'App\Listeners\Internal\Project\SourceAPI\Fulcrum\Project\ProjectLaunch@launchProject'
        );

        $events->listen(
            SourceAPIEvents::PROJECT_RESUME,
            'App\Listeners\Internal\Project\SourceAPI\Fulcrum\Project\ProjectResume@resumeProject'
        );

        $events->listen(
            SourceAPIEvents::PROJECT_PAUSED,
            'App\Listeners\Internal\Project\SourceAPI\Fulcrum\Project\ProjectPause@pauseProject'
        );

        $events->listen(
            SourceAPIEvents::PROJECT_CLOSED,
            'App\Listeners\Internal\Project\SourceAPI\Fulcrum\Project\ProjectClose@closeProject'
        );
    }
}
