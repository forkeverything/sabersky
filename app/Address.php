<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'address_1',
        'address_2',
        'state',
        'country',
        'zip',
        'phone',
        'primary',
        'owner_id',
        'owner_type'
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
    
}
