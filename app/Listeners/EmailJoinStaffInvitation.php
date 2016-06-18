<?php

namespace App\Listeners;

use App\Events\InvitedStaffMember;
use App\Mailers\UserMailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailJoinStaffInvitation
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
     * @param  InvitedStaffMember  $event
     * @return void
     */
    public function handle(InvitedStaffMember $event)
    {
        $this->userMailer->sendNewUserInvitation($event->invitee, $event->inviter);
    }
}
