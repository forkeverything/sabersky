<?php

namespace App\Listeners;

use App\Events\NewCompanySignedUp;
use App\Mailers\UserMailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailWelcomeMessage implements ShouldQueue
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
     * @param  NewCompanySignedUp  $event
     * @return void
     */
    public function handle(NewCompanySignedUp $event)
    {
        $this->userMailer->sendWelcomeEmail($event->company, $event->user);
    }
}
