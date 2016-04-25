<?php

namespace App\Policies;

use App\Address;
use App\User;
use App\Vendor;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AddressPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function edit(User $user, Address $address)
    {
        $canEditModel = false;
        $type = $address->owner_type;
        switch ($type) {
            case 'vendor':
                $canEditModel = Gate::allows('vendor_manage') && Gate::allows('edit', Vendor::find($address->owner_id));
                break;
            case 'company':
                $canEditModel = Auth::user()->company_id === $address->owner_id;
                break;
        }
        return $canEditModel;
    }
}
