<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        // TODO ::: Share logged-user with EVERY view - Find way to bind to Vue and circumvent root instance error
//        view()->composer('*', function ($view) {
//            $user = null;
//            if (Auth::check()) $user = auth()->user()->load('company', 'company.address', 'company.settings', 'role');
//            $view->with('user', $user);
//        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
