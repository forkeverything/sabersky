<?php

namespace App\Listeners;

use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogUserLastLogin
{
    /**
     * On login, save the time as the last_login time for
     * the User
     *
     * @param Login $event
     */
    public function handle(Login $event)
    {
        $event->user->last_login = Carbon::now();
        $event->user->save();
    }
}
