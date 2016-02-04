<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\PurchaseRequest
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $quantity
 * @property \Carbon\Carbon $due
 * @property string $state
 * @property boolean $urgent
 * @property integer $item_id
 * @property integer $project_id
 * @property integer $user_id
 * @property-read \App\Item $item
 * @property-read \App\Project $project
 * @property-read \App\User $user
 */
class PurchaseRequest extends Model
{
    protected $fillable = [
        'quantity',
        'due',
        'state',
        'urgent',
        'project_id',
        'item_id',
        'user_id'
    ];

    protected $dates = [
        'due'
    ];


    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStateAttribute($property)
    {
        return ucfirst($property);
    }

    public function lineItems()
    {
        return $this->hasMany(LineItem::class);
    }


}
