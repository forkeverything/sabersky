<?php

namespace App\Listeners;

use App\Events\PurchaseRequestMade;
use App\Mailers\UserMailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailNewRequestNotification
{
    /**
     * @var UserMailer
     */
    private $userMailer;

    /**
     * Create the event listener.
     *
     * @param UserMailer $userMailer
     */
    public function __construct(UserMailer $userMailer)
    {
        $this->userMailer = $userMailer;
    }

    /**
     * Handle the event.
     *
     * @param  PurchaseRequestMade  $event
     * @return void
     */
    public function handle(PurchaseRequestMade $event)
    {
        foreach ($event->purchaseRequest->project->teamMembers as $user) {
            if($user->can('po_submit')) $this->userMailer->sendPurchaseRequestNotification($event->purchaseRequest, $user, $event->requester);
        }
    }
}
