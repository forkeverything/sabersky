<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\PurchaseOrderAdditionalCost
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $name
 * @property string $type
 * @property integer $amount
 * @property integer $purchase_order_id
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseOrderAdditionalCost whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseOrderAdditionalCost whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseOrderAdditionalCost whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseOrderAdditionalCost whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseOrderAdditionalCost whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseOrderAdditionalCost whereAmount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseOrderAdditionalCost wherePurchaseOrderId($value)
 * @mixin \Eloquent
 */
class PurchaseOrderAdditionalCost extends Model
{
    protected $fillable = [
        'name',
        'type',
        'amount'
    ];

    /**
     * When we first set the Cost Type, we display the User's currency. But
     * we want to store it as the string 'fixed' and then parse it as the
     * currently selected currency, which can be changed.
     *
     * @param $value
     */
    public function setTypeAttribute($value)
    {
        $type = $value === '%' ? '%' : 'fixed';
        $this->attributes['type'] = $type;
    }
}
