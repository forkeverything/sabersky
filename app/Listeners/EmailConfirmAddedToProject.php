<?php

namespace App\Listeners;

use App\Events\AddedTeamMemberToProject;
use App\Mailers\UserMailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailConfirmAddedToProject implements ShouldQueue
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
     * @param  AddedTeamMemberToProject  $event
     * @return void
     */
    public function handle(AddedTeamMemberToProject $event)
    {
        $this->userMailer->sendConfirmAddedToProject($event->project, $event->addedUser, $event->managingUser);
    }
}
