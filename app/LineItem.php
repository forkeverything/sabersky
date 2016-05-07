<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\LineItem
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $quantity
 * @property float $price
 * @property \Carbon\Carbon $payable
 * @property \Carbon\Carbon $delivery
 * @property boolean $delivered
 * @property boolean $paid
 * @property string $status
 * @property integer $purchase_order_id
 * @property integer $purchase_request_id
 * @property-read \App\PurchaseRequest $purchaseRequest
 * @property-read \App\PurchaseOrder $purchaseOrder
 * @method static \Illuminate\Database\Query\Builder|\App\LineItem whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LineItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LineItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LineItem whereQuantity($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LineItem wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LineItem wherePayable($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LineItem whereDelivery($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LineItem whereDelivered($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LineItem wherePaid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LineItem whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LineItem wherePurchaseOrderId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LineItem wherePurchaseRequestId($value)
 * @mixin \Eloquent
 */
class LineItem extends Model
{
    protected $fillable = [
        'quantity',
        'price',
        'payable',
        'delivery',
        'delivered',
        'status',
        'purchase_order_id',
        'purchase_request_id'
    ];

    protected $dates = [
        'payable',
        'delivery'
    ];


    /**
     * Set 'payable' field as Carbon Date
     *
     * @param $value
     */
    public function setPayableAttribute($value)
    {
        if($value) $this->attributes['payable'] = Carbon::createFromFormat('d/m/Y', $value);
    }

    /**
     * Set 'delivery' as Carbon Date
     *
     * @param $value
     */
    public function setDeliveryAttribute($value)
    {
        if($value) $this->attributes['delivery'] = Carbon::createFromFormat('d/m/Y', $value);
    }

    /**
     * Line Item is fulfilling a single Purchase Request
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class);
    }

    /**
     * Line Item can only also belong to one Purcahse Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    /**
     * Takes a limit and sees if this Line Item's subtotal, price * quantity
     * is over the limit.
     *
     * Tested in RuleTest
     * 
     * @param $limit
     * @return bool
     */
    public function subtotalExceeds($limit)
    {
        return ($this->price * $this->quantity) > $limit;
    }

    /**
     * Check if the Item this Line Item is servicing's price is over the Item's mean
     * by a percentage (0 - 100)
     *
     * @param $percentage
     * @return bool
     */
    public function itemPriceIsOverMeanBy($percentage)
    {
        if(! $itemMean = $this->purchaseRequest->item->mean) return false;
        $meanDiff = ($this->price - $itemMean) / $itemMean;
        return $meanDiff > ($percentage / 100);
    }

}
