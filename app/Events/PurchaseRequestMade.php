<?php

namespace App\Events;

use App\Events\Event;
use App\Project;
use App\PurchaseRequest;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PurchaseRequestMade extends Event
{
    use SerializesModels;
    /**
     * @var PurchaseRequest
     */
    public $purchaseRequest;
    /**
     * @var User
     */
    public $recipient;
    /**
     * @var User
     */
    public $requester;

    /**
     * Create a new event instance.
     *
     * @param PurchaseRequest $purchaseRequest
     * @param User $requester
     */
    public function __construct(PurchaseRequest $purchaseRequest, User $requester)
    {
        $this->purchaseRequest = $purchaseRequest;
        $this->requester = $requester;
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
