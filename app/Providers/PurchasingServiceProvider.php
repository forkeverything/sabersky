<?php

namespace App\Providers;

use App\Company;
use App\Project;
use App\PurchaseOrder;
use App\PurchaseRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Validator;

class PurchasingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        /*
         * For the sake of consistency and mitigate risk of conflicting counts, this should be
         * the only place where 'pr_count' & 'po_count' are modified. They should NEVER be
         * changed elsewhere in the App.
         */

        // Whenever we create a PR
        PurchaseRequest::creating(function ($purchaseRequest) {

            // Get the statistics for the PR's Project's Company
            $stats  = Project::find($purchaseRequest->project_id)->company->statistics;

            // Fetch the current number of PRs within the company
            $counter = $stats->pr_count;

            // Set the PR number & Update Stats table
            $purchaseRequest->number = $stats->pr_count = $counter + 1;

            $stats->save();

            // return true to proceed with saving
            return true;
        });

        // Same thing as PR for Orders
        PurchaseOrder::creating(function ($purchaseOrder) {
            // We can assume all POs MUST belong to a company
            $stats = Company::findOrFail($purchaseOrder->company_id)->statistics;

            // How many POs we have now
            $counter = $stats->po_count;

            // Set the PR number & update stats table
            $purchaseOrder->number = $stats->po_count = $counter + 1;

            $stats->save();

            // return true to proceed with saving model
            return true;
        });


        // Custom validation Rules for purchasing
        Validator::extend('line_item_quantity_valid', function($attribute, $value, $parameters, $validator) {
            return PurchaseRequest::find($value['id'])->quantity >= $value['order_quantity'];
        });

        Validator::extend('pr_state_open', function($attribute, $value, $parameters, $validator) {
            return $value['state'] === 'open';
        });

         Validator::extend('pr_can_fulfill', function($attribute, $value, $parameters, $validator) {
             return Gate::allows('fulfill', PurchaseRequest::find($value['id']));
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
