<?php

namespace App;

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

    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

}
