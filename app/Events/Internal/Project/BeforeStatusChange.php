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

class BeforeStatusChange
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $project, $nextStatus, $currentStatus;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Project $project, ProjectStatus $nextStatus = null, ProjectStatus $currentStatus = null)
    {
        $this->project = $project;
        $this->nextStatus = $nextStatus;
        $this->currentStatus = $currentStatus;
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
