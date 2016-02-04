<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Item
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $name
 * @property string $specification
 * @property integer $project_id
 */
class Item extends Model
{
    protected $fillable = [
        'name',
        'specification',
        'project_id'
    ];

    protected $appends = [
        'new',
        'mean'
    ];

    public function getNameAttribute($property)
    {
        return ucfirst($property);
    }

    /**
     * An item can be requested multiple times.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchaseRequests()
    {
        return $this->hasMany(PurchaseRequest::class);
    }

    public function lineItems()
    {
        return $this->hasManyThrough(LineItem::class, PurchaseRequest::class);
    }

    public function getNewAttribute()
    {
        return $this->lineItems->count() < 2;
    }

    public function getMeanAttribute()
    {
//        $numOrdered = array_sum($this->lineItems()->where(function($query) {
//            return true;
//        })->pluck('quantity')->toArray());
        $test = $this->lineItems()->join('purchase_orders', '')
        return $test;


        /*********
         * NEED TO FIND THE RIGHT LINE ITEMS THAT AREN'T SUBMITTED YET
         */






        if ($numOrdered) {
            $sumOrderedValue = 0;
            foreach ($this->lineItems->pluck('quantity', 'price') as $quantity => $price) {
                $sumOrderedValue += ($quantity * $price);
            }
            $mean = $sumOrderedValue / $numOrdered;
            return $mean;
        }
        return null;
    }
}
