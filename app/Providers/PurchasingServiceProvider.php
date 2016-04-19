<?php

namespace App\Providers;

use App\Project;
use App\PurchaseRequest;
use Illuminate\Support\ServiceProvider;

class PurchasingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Whenever we create a PR
        PurchaseRequest::creating(function ($purchaseRequest) {

            // Get the statistics for the PR's Project's Company
            $stats  = Project::find($purchaseRequest->project_id)->company->statistics;
            // Fetch the current number of PRs within the company
            $counter = $stats->pr_count;

            // Set the PR number
            $purchaseRequest->number = $counter + 1;
            // Update Stats table
            $stats->pr_count = $counter + 1;
            $stats->save();

            // return true to proceed with saving
            return true;
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
