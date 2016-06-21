<?php

namespace App\Extensions;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class ExtendedEloquentUserProvider extends EloquentUserProvider
{

    /**
     * Over-write Eloquent's default validateCredentials() to also
     * check if User is active
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials)
    {

        // Custom checker here
        if(! $user->active) {

            flash()->error('Account has been deactivated');

            return false;
        }

        $plain = $credentials['password'];

        return $this->hasher->check($plain, $user->getAuthPassword());
    }
}

