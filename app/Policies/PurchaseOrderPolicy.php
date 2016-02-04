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

    public function approve(User $user, PurchaseOrder $purchaseOrder)
    {
        if ($user->role->position == 'Director') {
            return true;
        } elseif ($user->role->position == 'Manager' && ! $purchaseOrder->over_high) {
            return true;
        }
    }
}
