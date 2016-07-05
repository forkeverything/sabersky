<?php

namespace App\Events;

use App\Events\Event;
use App\PurchaseOrder;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PurchaseOrderUpdated extends Event implements ShouldBroadcast
{
    use SerializesModels;

    /**
     * @var PurchaseOrder
     */
    public $purchaseOrder;

    protected $channels;

    /**
     * Create a new event instance.
     *
     * @param PurchaseOrder $purchaseOrder
     */
    public function __construct(PurchaseOrder $purchaseOrder)
    {
        $this->purchaseOrder = $purchaseOrder;

        // Send out to all company employees
        $this->channels = $this->purchaseOrder->company->employees->pluck('id')->map(function ($id) {
            return 'private-user.' . $id;
        })->toArray();

        /*
         * For some reason if we get the array within broadcastOn(), it doesn't work.
         */
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return $this->channels;
    }
}
