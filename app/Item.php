<?php

namespace App;

use App\Project;
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

    public function projects()
    {
        return $this->belongsToMany(Project::class);
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
        return $this->approvedLineItems()->count() < 1;
    }

    public function getMeanAttribute()
    {
        $numOrdered = array_sum($this->approvedLineItems()->pluck('quantity')->toArray());

        if ($numOrdered) {
            $sumOrderedValue = 0;
            foreach ($this->approvedLineItems()->pluck('quantity', 'price') as $quantity => $price) {
                $sumOrderedValue += ($quantity * $price);
            }
            return $sumOrderedValue / $numOrdered;
        }
        return null;
    }

    protected function approvedLineItems()
    {
        return $this->lineItems()->join('purchase_orders', 'line_items.purchase_order_id', '=', 'purchase_orders.id')
            ->where('purchase_orders.submitted', 1)
            ->where('purchase_orders.status', 'approved')
            ->get(['line_items.*']);
    }
}
