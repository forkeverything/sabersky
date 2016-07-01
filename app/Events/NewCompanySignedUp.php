<?php

namespace App\Events;

use App\Company;
use App\Events\Event;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewCompanySignedUp extends Event implements ShouldBroadcast
{
    use SerializesModels;
    /**
     * @var Company
     */
    public $company;
    /**
     * @var User
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param Company $company
     * @param User $user
     */
    public function __construct(Company $company, User $user)
    {
        $this->company = $company;
        $this->user = $user;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['system'];
    }
}
