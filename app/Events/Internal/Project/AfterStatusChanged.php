<?php

namespace App\Events\Internal\Project;

use App\Models\Project\Project;
use App\Models\Project\ProjectStatus;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AfterStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $project, $previousStatusObject, $currentStatusObject;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Project $project, ProjectStatus $previousStatusObject, ProjectStatus $currentStatusObject)
    {
        $this->project = $project;
        $this->previousStatusObject = $previousStatusObject;
        $this->currentStatusObject = $currentStatusObject;
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
