<?php

namespace App\Policies;

use App\PurchaseRequest;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Gate;

class PurchaseRequestPolicy
{
    use HandlesAuthorization;

    /**
     * A User is authorized to view a PR, if they are
     * a part of the same Project
     *
     * @param User $user
     * @param PurchaseRequest $purchaseRequest
     * @return mixed
     */
    public function view(User $user, PurchaseRequest $purchaseRequest)
    {
        return $user->projects->contains($purchaseRequest->project);
    }

    /**
     * :::UNTESTED:::
     * 
     * Authorized to make changes to a PR if a User is allows to view
     * the PR and also if the User Role has authorization to make
     * Purchase Requests.
     *
     * @param User $user
     * @param PurchaseRequest $purchaseRequest
     * @return bool
     */
    public function change(User $user, PurchaseRequest $purchaseRequest)
    {
        return $this->view($user, $purchaseRequest) && Gate::allows('pr_make');
    }

    /**
     * :::UNTESTED:::
     *
     * A User is allowed to fulfill (make a order) for a PR if they are
     * allowed to view it (belong to same project) and they are also
     * authorized to submit Purchase Orders.
     * 
     * @param User $user
     * @param PurchaseRequest $purchaseRequest
     * @return bool
     */
    public function fulfill(User $user, PurchaseRequest $purchaseRequest)
    {
        return $this->view($user, $purchaseRequest) && Gate::allows('po_submit');
    }


}
