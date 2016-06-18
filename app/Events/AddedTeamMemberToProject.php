<?php

namespace App\Events;

use App\Events\Event;
use App\Project;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AddedTeamMemberToProject extends Event
{
    use SerializesModels;
    /**
     * @var Project
     */
    public $project;
    /**
     * @var User
     */
    public $addedUser;
    /**
     * @var User
     */
    public $managingUser;

    /**
     * Create a new event instance.
     *
     * @param Project $project
     * @param User $addedUser
     * @param User $managingUser
     */
    public function __construct(Project $project, User $addedUser, User $managingUser)
    {
        $this->project = $project;
        $this->addedUser = $addedUser;
        $this->managingUser = $managingUser;
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
