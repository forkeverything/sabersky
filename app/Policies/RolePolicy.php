<?php

namespace App\Policies;

use App\Role;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function attaching(User $user, Role $role)
    {
        return $role->company_id === $user->company_id && $role->position !== 'admin';
    }
}
