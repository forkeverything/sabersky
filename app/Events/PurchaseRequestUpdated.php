<?php

namespace App\Events;

use App\Events\Event;
use App\PurchaseRequest;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PurchaseRequestUpdated extends Event implements ShouldBroadcast
{
    use SerializesModels;
    /**
     * @var PurchaseRequest
     */
    public $purchaseRequest;

    /**
     * Create a new event instance.
     *
     * @param PurchaseRequest $purchaseRequest
     */
    public function __construct(PurchaseRequest $purchaseRequest)
    {
        $this->purchaseRequest = $purchaseRequest;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        // Broadcast to every user a part of project
        $channels = [];
        foreach ($this->purchaseRequest->project->teamMembers as $user) {
            array_push($channels, 'user.' . $user->id);
        }
        return $channels;
    }
}
