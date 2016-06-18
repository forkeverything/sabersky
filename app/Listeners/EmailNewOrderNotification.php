<?php

namespace App\Listeners;

use App\Events\PurchaseOrderSubmitted;
use App\Mailers\UserMailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailNewOrderNotification
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
     * @param  PurchaseOrderSubmitted  $event
     * @return void
     */
    public function handle(PurchaseOrderSubmitted $event)
    {
        $approvers = [];

        // Grab all potential users that can approve our PO
        foreach ($event->purchaseOrder->rules as $rule) {
            foreach ($rule->roles as $role) {
                foreach ($role->users as $user) {
                    array_push($approvers, $user);
                }
            }
        }

        // Only send 1 notification per User
        $approvers = collect($approvers)->unique();

        foreach ($approvers as $user) {
            $this->userMailer->sendPurchaseOrderNotification($event->purchaseOrder, $user, $event->submitter);
        }
    }
}
