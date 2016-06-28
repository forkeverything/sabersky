<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;

/**
 * App\BankAccount
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $bank_name
 * @property string $account_name
 * @property string $account_number
 * @property string $bank_phone
 * @property string $bank_address
 * @property string $swift
 * @property boolean $primary
 * @property boolean $active
 * @property integer $vendor_id
 * @property-read \App\Vendor $vendor
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PurchaseOrder[] $purchaseOrders
 * @method static \Illuminate\Database\Query\Builder|\App\BankAccount whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BankAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BankAccount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BankAccount whereBankName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BankAccount whereAccountName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BankAccount whereAccountNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BankAccount whereBankPhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BankAccount whereBankAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BankAccount whereSwift($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BankAccount wherePrimary($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BankAccount whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BankAccount whereVendorId($value)
 * @mixin \Eloquent
 */
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
        return $this->hasMany(PurchaseOrder::class, 'vendor_bank_account_id');
    }

    /**
     * Deactives (soft-deletes) this model
     * @return bool
     */
    public function deactivate()
    {
        $this->active = 0;
        return $this->save();
    }

    /**
     * Try to delete a Bank Account if there are no POs
     * made to it for payment. Otherwise we'll just
     * soft-delete by de-activating it.
     * 
     * @throws Exception
     */
    public function deleteOrDeactivate()
    {
        if($this->purchaseOrders->count() > 0) {
            return $this->deactivate();
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
