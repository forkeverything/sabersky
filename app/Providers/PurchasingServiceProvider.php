<?php

namespace App\Providers;

use App\Company;
use App\Project;
use App\PurchaseOrder;
use App\PurchaseRequest;
use App\Role;
use Illuminate\Support\Facades\Auth;
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
            $stats = Project::find($purchaseRequest->project_id)->company->statistics;

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

        // PR & Line Items

        // Line Item - Can't have quantities greater than their Request's quantity (can't order more than we need)
        Validator::extend('line_item_quantity', function ($attribute, $value, $parameters, $validator) {
            return $value['order_quantity'] >= 1 && $value['quantity'] >= $value['order_quantity'];
        });

        // Line Item - Can't have different price for same item in the same (single) order
        Validator::extend('line_item_price', function ($attribute, $value, $parameters, $validator) {

            $currentLineItem = $value;
            $currentItem = PurchaseRequest::find($currentLineItem["id"])->item;

            $allLineItems = collect(array_get($validator->getData(), "line_items"));

            $lineItemsWithSameItem = $allLineItems->filter(function ($lineItem) use ($currentItem) {
                $item = PurchaseRequest::find($lineItem["id"])->item;
                return $currentItem->id == $item->id;
            });

            $samePrice = $lineItemsWithSameItem->unique('order_price');

            return count($samePrice) === 1;
        });

        Validator::extend('pr_state_open', function ($attribute, $value, $parameters, $validator) {
            return $value['state'] === 'open';
        });

        Validator::extend('pr_can_fulfill', function ($attribute, $value, $parameters, $validator) {
            return Gate::allows('fulfill', PurchaseRequest::find($value['id']));
        });

        // Rules

        Validator::extend('rule_property', function ($attribute, $value, $parameters, $validator) {
            $properties = getRuleProperties();
            $propertyIDs = $properties->pluck('id')->toArray();
            return in_array($value, $propertyIDs);
        });


        Validator::extend('rule_trigger', function ($attribute, $value, $parameters, $validator) {
            $propertyID = array_get($validator->getData(), "rule_property_id");
            $properties = getRuleProperties();
            $triggerIDs = collect($properties->where('id', (int)$propertyID)->first()->triggers)->pluck('id')->all();
            return in_array($value, $triggerIDs);
        });

        Validator::extend('rule_unique', function ($attribute, $value, $parameters, $validator) {

            $formData = $validator->getData();

            $propertyId = array_get($formData, "rule_property_id");
            $triggerId = array_get($formData, "rule_trigger_id");
            $currencyId = array_get($formData, "currency_id");

            $hasCurrency = array_get($formData, "has_currency");

            $query = \DB::table('rules')
                        ->select(\DB::raw(1))
                        ->where('rule_property_id', '=', $propertyId)
                        ->where('rule_trigger_id', '=', $triggerId);

            if($hasCurrency) $query->where('currency_id', '=', $currencyId);

            return ! $query->get();
        });

        Validator::extend('rule_roles', function ($attribute, $value, $parameters, $validator) {
            $roleIds = $value;
            $validRoles = true;

            foreach ($roleIds as $roleId) {
                $role = Role::find($roleId);
                if (!Auth::user()->company->roles->contains($role)) {
                    $validRoles = false;
                    break;
                }
            }

            return $validRoles;

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
