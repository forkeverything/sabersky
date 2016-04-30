<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'contact_person',
        'address_1',
        'address_2',
        'city',
        'state',
        'country_id',
        'zip',
        'phone',
        'primary',
        'owner_id',
        'owner_type'
    ];

    protected $appends = [
        'country'
    ];

    /**
     * An Address can be owned by either a Company or a Vendor model.
     * ie. Addresses are polymorphic!
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function owner()
    {
        return $this->morphTo();
    }

    /**
     * Append the Address's country's name
     * @return mixed
     */
    public function getCountryAttribute()
    {
        return Country::find($this->country_id)->name;
    }

    /**
     * Sets this Address as the primary address for a owner Model
     * @return bool
     */
    public function setAsPrimary()
    {
        // Grab all the addresses that belong to the same model
        $allAddresses = $this->owner->addresses;
        // unset them
        foreach ($allAddresses as $address) {
            $address->unsetPrimary();
        }
        // set this one
        $this->primary = 1;
        return $this->save();
    }

    /**
     * Unsets this address as Primary
     * @return bool
     */
    public function unsetPrimary()
    {
        $this->primary = 0;
        return $this->save();
    }
}
