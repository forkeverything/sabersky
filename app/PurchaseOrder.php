<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'approved',
        'paid',
        'submitted',
        'user_id',
        'project_id',
        'vendor_id'
    ];

    protected $appends = [
        'total'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function lineItems()
    {
        return $this->hasMany(LineItem::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTotalAttribute()
    {
        $total = 0;
        foreach ($this->lineItems as $lineItem) {
            $total = $total + ($lineItem->price * $lineItem->quantity);
        }
        return number_format($total) . ' Rp';
    }

}
