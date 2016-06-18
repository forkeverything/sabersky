<?php

namespace App\Events;

use App\Events\Event;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class InvitedStaffMember extends Event
{
    use SerializesModels;
    /**
     * @var User
     */
    public $invitee;
    /**
     * @var User
     */
    public $inviter;

    /**
     * Create a new event instance.
     *
     * @param User $invitee
     * @param User $inviter
     */
    public function __construct(User $invitee, User $inviter)
    {
        //
        $this->invitee = $invitee;
        $this->inviter = $inviter;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
