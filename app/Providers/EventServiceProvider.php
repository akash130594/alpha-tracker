<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Class EventServiceProvider.
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Internal\Project\BeforeStatusChange' => [
            'App\Listeners\Internal\Project\Status\Before\TBDListener',
            'App\Listeners\Internal\Project\Status\Before\PendingListener',
            'App\Listeners\Internal\Project\Status\Before\CancelledListener',
            'App\Listeners\Internal\Project\Status\Before\LiveListener',
            'App\Listeners\Internal\Project\Status\Before\HoldListener',
            'App\Listeners\Internal\Project\Status\Before\ClosedListener',
            'App\Listeners\Internal\Project\Status\Before\IDReceivedListener',
            'App\Listeners\Internal\Project\Status\Before\IncentivePaidListener',
            'App\Listeners\Internal\Project\Status\Before\ArchivedListener',
        ],
        'App\Events\Internal\Project\AfterStatusChanged' => [
            'App\Listeners\Internal\Project\Status\After\TBDListener',
            'App\Listeners\Internal\Project\Status\After\PendingListener',
            'App\Listeners\Internal\Project\Status\After\CancelledListener',
            'App\Listeners\Internal\Project\Status\After\LiveListener',
            'App\Listeners\Internal\Project\Status\After\HoldListener',
            'App\Listeners\Internal\Project\Status\After\ClosedListener',
            'App\Listeners\Internal\Project\Status\After\IDReceivedListener',
            'App\Listeners\Internal\Project\Status\After\IncentivePaidListener',
            'App\Listeners\Internal\Project\Status\After\ArchivedListener',
        ],
    ];

    /**
     * Class event subscribers.
     *
     * @var array
     */
    protected $subscribe = [
        /*
         * Frontend Subscribers
         */

        /*
         * Auth Subscribers
         */
        \App\Listeners\Frontend\Auth\UserEventListener::class,

        /*
         * Backend Subscribers
         */

        /*
         * Auth Subscribers
         */
        \App\Listeners\Backend\Auth\User\UserEventListener::class,
        \App\Listeners\Backend\Auth\Role\RoleEventListener::class,

        /*ProjectSourceAPI Subscribers*/
        \App\Listeners\Internal\Project\SourceAPI\Fulcrum\FulcrumAPI::class,
        \App\Listeners\Internal\Project\SourceAPI\SJPanel\SJPanelAPI::class,
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
