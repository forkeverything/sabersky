<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Vendor extends Model
{
    protected $fillable = [
        'name',
        'description',
        'buyer_company_id',
        'verified',
        'seller_company_id'
    ];

    protected $appends = [
        'numberPO',
        'averagePO',
        'contactedBy'
    ];


    /**
     * A Vendor is always owned as a record of a buying
     * Company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function buyer()
    {
        return $this->belongsTo(Company::class, 'buyer_company_id');
    }

    /**
     * A Vendor can also be optionally linked to another Company
     * in the system, making the Company the seller.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seller()
    {
        return $this->belongsTo(Company::class, 'seller_company_id');
    }

    /**
     * Accessor to return registered Company Name if
     * one exists
     * 
     * @param $val
     * @return mixed
     */
    public function getNameAttribute($val)
    {
        if ($this->seller) {
            return $this->seller->name;
        }
        return $val;
    }

    /**
     * Makes it easier to link a Company as a seller. ie. Link
     * a Company that also has a registered profile here in
     * our records.
     *
     * @param Company $company
     * @return bool
     */
    public function linkSeller(Company $company)
    {
        $this->seller_company_id = $company->id;
        return $this->save();
    }

    /**
     * Vendor can have many Bank Accounts
     * on record
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class);
    }


    /**
     * Vendor can have many addresses on record
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

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
