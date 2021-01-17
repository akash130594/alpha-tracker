<?php

namespace App\Events\Internal\Project\SourceAPI;

use App\Models\Project\Project;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SourceAPIEvents
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    const PROJECT_CREATED = 'project.created';
    const PROJECT_LAUNCHED = 'project.launched';
    const PROJECT_PAUSED = 'project.paused';
    const PROJECT_RESUME = 'project.resume';
    const PROJECT_CLOSED = 'project.closed';


    const PROJECT_UPDATED = 'project.updated';




    const PROJECT_RECONCILE = 'project.reconcile';

    const QUALIFICATION_CREATED = 'qualification.created';
    const QUALIFICATION_UPDATED = 'qualification.updated';

    const QUOTA_CREATED = 'quota.created';
    const QUOTA_UPDATED = 'quota.updated';

    const RECONTACT_CREATED = 'recontact.created';
    const RECONTACT_UPDATED = 'recontact.updated';

    const SURVEY_GROUP_CREATED = 'survey_group.created';
    const SURVEY_GROUP_UPDATED = 'survey_group.updated';
    const SURVEY_GROUP_ADDED_SURVEY = 'survey_group.added_survey';
    const SURVEY_GROUP_REMOVED_SURVEY = 'survey_group.removed_survey';

    public $project;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
