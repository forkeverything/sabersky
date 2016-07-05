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
        return $this->purchaseRequest->project->teamMembers->pluck('id')->map(function ($id) {
            return 'user.' . $id;
        })->toArray();
    }
}
