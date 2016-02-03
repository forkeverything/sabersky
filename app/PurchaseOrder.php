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

}
