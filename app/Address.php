<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Address
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $contact_person
 * @property string $phone
 * @property string $address_1
 * @property string $address_2
 * @property string $city
 * @property string $zip
 * @property string $state
 * @property boolean $primary
 * @property integer $owner_id
 * @property string $owner_type
 * @property integer $country_id
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $owner
 * @property-read mixed $country
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereContactPerson($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereAddress1($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereAddress2($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereCity($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereZip($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereState($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address wherePrimary($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereOwnerId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereOwnerType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereCountryId($value)
 * @mixin \Eloquent
 */
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
     *
     * Wrapper func to manually set an Address's owner
     * using a type (string) and id (int)
     * 
     * @param $type
     * @param $id
     * @return $this
     */
    public function setOwner($type, $id)
    {
        $this->owner_type = $type;
        $this->owner_id = $id;
        $this->save();
        return $this;
    }

    /**
     * Append the Address's country's name
     *
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
        $this->unsetPrimaryForAllAddresses();
        // set this one
        $this->primary = 1;
        return $this->save();
    }

    /**
     * Unsets primary for all addresses that belong to the same owner
     * as this Address
     *
     * @return $this
     */
    public function unsetPrimaryForAllAddresses()
    {
        // If parent model has multiple addresses
        if ($allAddresses = $this->owner->addresses) {
            // unset each one
            foreach ($allAddresses as $address) {
                $address->unsetPrimary();
            }
        }

        return $this;
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
