<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
