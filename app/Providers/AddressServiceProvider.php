<?php

namespace App\Providers;

use App\Company;
use App\Vendor;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AddressServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Over-write the default of using full class name as Polymorphic Model Type
        Relation::morphMap([
            // Instead use singular of table name
            'vendor' => Vendor::class,
            'company' => Company::class
        ]);
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
