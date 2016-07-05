<?php

namespace App\Events;

use App\Events\Event;
use App\LineItem;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LineItemUpdated extends Event implements ShouldBroadcast
{
    use SerializesModels;

    /**
     * Channels to broadcast on...
     *
     * @var array
     */
    protected $channels;

    /**
     * Updated Line Item Model
     * 
     * @var LineItem
     */
    public $lineItem;

    /**
     * Create a new event instance.
     *
     * @param LineItem $lineItem
     */
    public function __construct(LineItem $lineItem)
    {
        $this->lineItem = $lineItem;
        $this->channels = $lineItem->purchaseOrder->company->employees->pluck('id')->map(function ($id) {
            return 'private-user.' . $id;
        })->toArray();
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
