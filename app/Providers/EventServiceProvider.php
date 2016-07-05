<?php

namespace App\Providers;


use App\Events\AddedTeamMemberToProject;
use App\Events\InvitedStaffMember;
use App\Events\NewCompanySignedUp;
use App\Events\PurchaseOrderSubmitted;
use App\Events\PurchaseRequestMade;
use App\Events\PurchaseRequestUpdated;
use App\Listeners\EmailConfirmAddedToProject;
use App\Listeners\EmailJoinStaffInvitation;
use App\Listeners\EmailNewOrderNotification;
use App\Listeners\EmailNewRequestNotification;
use App\Listeners\EmailWelcomeMessage;
use App\Listeners\LogUserLastLogin;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],
        Login::class => [
            LogUserLastLogin::class
        ],
        NewCompanySignedUp::class => [
            EmailWelcomeMessage::class,
        ],
        InvitedStaffMember::class => [
            EmailJoinStaffInvitation::class
        ],
        AddedTeamMemberToProject::class => [
            EmailConfirmAddedToProject::class
        ],
        PurchaseRequestMade::class => [
            EmailNewRequestNotification::class
        ],
        PurchaseOrderSubmitted::class => [
            EmailNewOrderNotification::class
        ],
        PurchaseRequestUpdated::class => []
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
