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
        'vendor_id',
        'primary'
    ];

    /**
     * A Bank Account can only ever belong to a Vendor Profile
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

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
        if($this->purchaseOrders->count() > 0) {
            throw new Exception("Can't remove a Bank Account with existing Purchase Orders");
        } else {
            return $this->delete();
        }
    }

    /**
     * Unsets this model as the primary Bank Account
     * @return bool
     */
    public function unsetPrimary()
    {
        $this->primary = 0;
        return $this->save();
    }

    /**
     * Sets this model, but first unsetting all other
     * Bank accounts associated to the same Vendor
     * @return bool
     */
    public function setPrimary()
    {
        foreach ($this->vendor->bankAccounts as $bankAccount) {
            $bankAccount->unsetPrimary();
        }

        $this->primary = 1;
        
        return $this->save();
    }
}
