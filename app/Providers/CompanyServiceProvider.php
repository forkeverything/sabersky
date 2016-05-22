<?php

namespace App\Providers;

use App\Company;
use App\CompanySettings;
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
            // We also want to create it's statistics & settings table
            $company->statistics()->create([]);
            $company->settings()->create([]);
        });

        CompanySettings::created(function ($settings) {
            // set USD as default currency
            $settings->currencyCountries()->attach(['840']);
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
