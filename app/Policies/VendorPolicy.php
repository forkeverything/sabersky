<?php

namespace App\Policies;

use App\User;
use App\Vendor;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class VendorPolicy
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

    /**
     * A User can view a Vendor if it belongs to the
     * User's Company
     *
     * @param User $user
     * @param Vendor $vendor
     * @return bool
     */
    public function view(User $user, Vendor $vendor)
    {
        return $user->company_id === $vendor->base_company_id;
    }


    /**
     * If a User can view a Vendor model and they also have permission to
     * manage vendors - then they can edit them.
     * 
     * @param User $user
     * @param Vendor $vendor
     * @return bool
     */
    public function edit(User $user, Vendor $vendor)
    {
        return $this->view($user, $vendor) && Gate::allows('vendor_manage');
    }

    /**
     * A User is only allowed to accept a Vendor model if the Vendor is
     * linked to the User's Company
     *
     * @param User $user
     * @param Vendor $vendor
     * @return bool
     */
    public function handleRequest(User $user, Vendor $vendor)
    {
        return $user->company_id === $vendor->linked_company_id;
    }
}
