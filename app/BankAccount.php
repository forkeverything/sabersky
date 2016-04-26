<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    /**
     * Mass-fillable fields for Bank Account
     * Information
     *
     * @var array
     */
    protected $fillable = [
        'bank_name',
        'account_name',
        'account_number',
        'bank_phone',
        'bank_address',
        'swift',
        'vendor_id'
    ];

    /**
     * A Bank Account could potentially have many POs made
     * to be paid to it.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    /**
     * Try to delete a Bank Account if there are no POs
     * made to it for payment
     * 
     * @throws Exception
     */
    public function tryDelete()
    {
        if($this->purchaseOrders) {
            throw new Exception("Can't remove a Bank Account with existing Purchase Orders");
        } else {
            $this->delete();
        }
    }
}
