<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;
    
    /**
     * An acting user (logged-in) can only
     * edit a user's information if they
     * are from the same company.
     *
     * @param User $actingUser
     * @param User $modifiedUser
     * @return bool
     */
    public function edit(User $actingUser, User $modifiedUser)
    {
        return $actingUser->company_id === $modifiedUser->company_id;
    }
}
