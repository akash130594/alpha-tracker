<?php

namespace App\Listeners\Internal\Project\Status\Before;

use App\Events\Internal\Project\BeforeStatusChange;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class IncentivePaidListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BeforeStatusChange  $event
     * @return void
     */
    public function handle(BeforeStatusChange $event)
    {
        //
    }
}
