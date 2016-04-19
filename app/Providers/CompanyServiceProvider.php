<?php

namespace App\Providers;

use App\Company;
use App\CompanyStatistics;
use Illuminate\Support\ServiceProvider;

class CompanyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Whenever we create Company model
        Company::created(function ($company) {
            // We also want to create it's statistics table
            $company->statistics()->create([]);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
