<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\PurchaseRequest
 *
 * @property integer $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property integer $quantity
 * @property Carbon $due
 * @property string $state
 * @property boolean $urgent
 * @property integer $item_id
 * @property integer $project_id
 * @property integer $user_id
 * @property-read \App\Item $item
 * @property-read \App\Project $project
 * @property-read \App\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LineItem[] $lineItems
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


    public function lineItems()
    {
        return $this->hasMany(LineItem::class);
    }

    public function setDueAttribute($value)
    {
        $this->attributes['due'] = Carbon::createFromFormat('d/m/Y', $value);
    }


}
