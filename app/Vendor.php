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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PurchaseOrder[] $purchaseOrders
 * @property-read mixed $number_p_o
 * @property-read mixed $average_p_o
 * @property-read mixed $contacted_by
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

    protected $appends = [
        'numberPO',
        'averagePO',
        'contactedBy'
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

    public function getNumberPOAttribute()
    {
        return $this->purchaseOrders()->count();
    }

    public function getAveragePOAttribute()
    {
        $numPO = $this->getNumberPOAttribute();
        $sumPO = 0;
        foreach ($this->purchaseOrders as $purchaseOrder) {
            $sumPO += $purchaseOrder->total;
        }
        return ($numPO) ?  $sumPO / $numPO : 0;
    }

    public function getContactedByAttribute()
    {
        $contactedBy = [];
        $userIds = $this->purchaseOrders()->groupBy('user_id')->pluck('user_id');
        foreach ($userIds as $userId) {
            array_push($contactedBy, User::find($userId)->name);
        }
        if ($contactedBy) {
            return implode(', ', $contactedBy);
        } else {
            return 'N/A';
        }
    }
}
