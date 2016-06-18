<?php

namespace App\Events;

use App\Events\Event;
use App\PurchaseOrder;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PurchaseOrderSubmitted extends Event
{
    use SerializesModels;
    
    /**
     * @var PurchaseOrder
     */
    public $purchaseOrder;
    /**
     * @var User
     */
    public $submitter;

    /**
     * Create a new event instance.
     *
     * @param PurchaseOrder $purchaseOrder
     * @param User $submitter
     */
    public function __construct(PurchaseOrder $purchaseOrder, User $submitter)
    {

        $this->purchaseOrder = $purchaseOrder;
        $this->submitter = $submitter;
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
