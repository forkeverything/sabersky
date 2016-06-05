<?php


namespace App\Utilities;


use App\LineItem;
use App\User;
use Carbon\Carbon;

class CalendarEventsPlanner
{

    protected $user;

    /**
     * Instantiate our planner with User so our methods can all access it
     *
     * CalendarEventsPlanner constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Static wrapper
     *
     * @param User $user
     * @return static
     */
    public static function forUser(User $user)
    {
        return new static($user);
    }

    /**
     * Date formatter
     *
     * @param Carbon $date
     * @return string
     */
    protected function formatDate(Carbon $date)
    {
        return $date->format('Y-m-d');
    }

    /**
     * Payable events
     *
     * @return array
     */
    public function getPayables()
    {
        $payableEvents = [];

        $lineItems = LineItem::whereIn('purchase_order_id', $this->user->company->purchaseOrders->pluck('id'))->get();
        foreach ($lineItems as $lineItem) {
            if (($date = $lineItem->payable) && ! $lineItem->paid) {
                $event = [
                    "id" => "pay-" . $lineItem->purchase_order_id,
                    "title" => "Payment",
                    "start" => $this->formatDate($date),
                    "url" => "/purchase_orders/" . $lineItem->purchase_order_id,
                    "className" => "event-payable"
                ];
                array_push($payableEvents, $event);
            }
        }
        return $payableEvents;
    }

    /**
     * Retrieve delivery date events
     *
     * @return array
     */
    public function getDeliveries()
    {
        $deliveryEvents = [];

        $lineItems = LineItem::whereIn('purchase_order_id', $this->user->company->purchaseOrders->pluck('id'))->get();
        foreach ($lineItems as $lineItem) {
            if (($date = $lineItem->delivery) && ! $lineItem->received) {
                $event = [
                    "id" => "delivery-" . $lineItem->purchase_order_id,
                    "title" => "Delivery",
                    "start" => $this->formatDate($date),
                    "url" => "/purchase_orders/" . $lineItem->purchase_order_id,
                    "className" => "event-delivery"
                ];
                array_push($deliveryEvents, $event);
            }
        }
        return $deliveryEvents;
    }

    /**
     * Receives an events (array) and returns only
     * unique events
     *
     * @param array $events
     * @return array
     */
    public function filterUnique(array $events)
    {
        $uniqueEvents = [];
        $eventHashes = [];

        foreach ($events as $event) {
            // Hash by event id and start date
            $hash = md5($event["id"] . $event["start"]);
            // If event is unique
            if (!isset($eventHashes[$hash])) {
                $eventHashes[$hash] = $hash;
                $uniqueEvents[] = $event;
            }
        }

        return $uniqueEvents;
    }

}