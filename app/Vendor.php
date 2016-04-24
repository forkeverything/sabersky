<?php

namespace App;

use App\Address;
use Illuminate\Database\Eloquent\Model;


class Vendor extends Model
{
    protected $fillable = [
        'name',
        'description',
        'base_company_id',
        'verified',
        'linked_company_id'
    ];

    protected $appends = [
        'numberPO',
        'averagePO',
        'allAddresses'
    ];


    /**
     * Mutator to save linked_company_id field as NULL
     * instead of an empty string.
     * 
     * @param $value
     */
    public function setLinkedCompanyIdAttribute($value)
    {
        if ( empty($value) ) {
            $this->attributes['linked_company_id'] = NULL;
        } else {
            $this->attributes['linked_company_id'] = $value;
        }
    }


    /**
     * A Vendor is always owned as a record of a base
     * Company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function baseCompany()
    {
        return $this->belongsTo(Company::class, 'base_company_id');
    }

    /**
     * A Vendor can also be optionally linked to another Company
     * in the system.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function linkedCompany()
    {
        return $this->belongsTo(Company::class, 'linked_company_id');
    }

    /**
     * Accessor to return linked Company Name if
     * one exists.
     *
     * @param $val
     * @return mixed
     */
    public function getNameAttribute($val)
    {
        if ($this->linkedCompany) {
            return $this->linkedCompany->name;
        }
        return $val;
    }

    /**
     * Set the 'linked_company_id' which will link a
     * Company to this Vendor profile.
     *
     * @param Company $company
     * @return bool
     */
    public function linkCompany(Company $company)
    {
        $this->linked_company_id = $company->id;
        return $this->save();
    }

    /**
     * Takes a base Company (usually owned by the logged User) and
     * a link Company and creates a new Vendor while linking the
     * Link Company in one process.
     *
     * @param Company $baseCompany
     * @param Company $linkCompany
     * @return static
     */
    public static function createAndLinkFromCompany(Company $baseCompany, Company $linkCompany)
    {
        return static::create([
            'name' => $linkCompany->name,
            'base_company_id' => $baseCompany->id,
            'linked_company_id' => $linkCompany->id
        ]);
    }

    /**
     * Verify the Vendor Link to Company
     * Model
     *
     * @return bool
     */
    public function verify()
    {
        $this->verified = 1;
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
     * Lots of Purchase Orders can be made to the same
     * Vendor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    /**
     * Number of Purchase Orders made to this Vendor
     *
     * @return mixed
     */
    public function getNumberPOAttribute()
    {
        return $this->purchaseOrders()->count();
    }

    /**
     * Calculate the average Price / PO for this
     * Vendor
     *
     * @return float|int
     */
    public function getAveragePOAttribute()
    {
        $numPO = $this->getNumberPOAttribute();
        $sumPO = 0;
        foreach ($this->purchaseOrders as $purchaseOrder) {
            $sumPO += $purchaseOrder->total;
        }
        return ($numPO) ? $sumPO / $numPO : 0;
    }

    /**
     * A Vendor can have many records of addresses
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->morphMany(Address::class, 'owner');
    }

    /**
     * Retrieve all known addresses for this Vendor. Perform
     * a look-up for both this Vendor's addresses as well
     * as the linked Company's registered addresses.
     *
     * @return mixed
     */
    public function getAllAddressesAttribute()
    {
        return $this->linkedCompany ? $this->addresses->merge($this->linkedCompany->addresses) : $this->addresses;
    }




}
