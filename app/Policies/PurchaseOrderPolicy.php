<?php

namespace App\Policies;

use App\PurchaseOrder;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchaseOrderPolicy
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
     * User is only allowed to view an order if they belong to the
     * same Company
     * 
     * @param User $user
     * @param PurchaseOrder $purchaseOrder
     * @return bool
     */
    public function view(User $user, PurchaseOrder $purchaseOrder)
    {
        return $user->company_id === $purchaseOrder->company_id;
    }
}
