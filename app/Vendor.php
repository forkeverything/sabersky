<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Vendor
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $name
 * @property string $phone
 * @property string $address
 * @property string $bank_name
 * @property string $bank_account_name
 * @property string $bank_account_number
 * @property integer $company_id
 */
class Vendor extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'address',
        'bank_account_name',
        'bank_account_number',
        'bank_name',
        'company_id'
    ];

    /**
     * A vendor / Supplier can service many
     * Purchase Orders.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}
