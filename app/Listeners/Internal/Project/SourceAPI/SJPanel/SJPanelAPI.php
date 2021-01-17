<?php

namespace App\Listeners\Internal\Project\SourceAPI\SJPanel;


use App\Events\Internal\Project\SourceAPI\SourceAPIEvents;

class SJPanelAPI
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
            'App\Listeners\Internal\Project\SourceAPI\SJPanel\Project\ProjectCreate@createProject'
        );

        $events->listen(
            SourceAPIEvents::PROJECT_LAUNCHED,
            'App\Listeners\Internal\Project\SourceAPI\SJPanel\Project\ProjectLaunch@launchProject'
        );

        $events->listen(
            SourceAPIEvents::PROJECT_RESUME,
            'App\Listeners\Internal\Project\SourceAPI\SJPanel\Project\ProjectResume@resumeProject'
        );
        $events->listen(
            SourceAPIEvents::PROJECT_PAUSED,
            'App\Listeners\Internal\Project\SourceAPI\SJPanel\Project\ProjectPause@pauseProject'
        );
        $events->listen(
            SourceAPIEvents::PROJECT_CLOSED,
            'App\Listeners\Internal\Project\SourceAPI\SJPanel\Project\ProjectClose@closeProject'
        );

    }
}
