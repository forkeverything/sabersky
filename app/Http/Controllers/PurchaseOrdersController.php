<?php

namespace App\Http\Controllers;

use App\Address;
use App\Http\Requests\ApprovePurchaseOrderRequest;
use App\Http\Requests\CreatePurchaseOrderRequest;
use App\Http\Requests\POStep1Request;
use App\Http\Requests\POStep2Request;
use App\Http\Requests\SaveLineItemRequest;
use App\Http\Requests\SubmitPurchaseOrderRequest;
use App\LineItem;
use App\PurchaseOrder;
use App\PurchaseRequest;
use App\Repositories\CompanyPurchaseOrdersRepository;
use App\User;
use App\Vendor;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PurchaseOrdersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('api.only', [
            'only' => ['apiPostSubmit']
        ]);
    }

    /**
     * Shows Purchase Order view for
     * all POs.
     *
     * @return mixed
     */
    public function getAll()
    {
        $breadcrumbs = [
            ['<i class="fa fa-shopping-basket"></i> Purchase Orders', '#'],
        ];
        return view('purchase_orders.all', compact('breadcrumbs'));
    }

    /**
     * API Endpoint that retrieves all POs which
     * belong to User's company.
     *
     * @param Request $request
     * @return mixed
     */
    public function apiGetAll(Request $request)
    {
        return CompanyPurchaseOrdersRepository::forCompany(Auth::user()->company)
                                              ->whereStatus($request->status)
            ->filterIntegerField('number', $request->number)
            ->hasRequestForProject($request->project_id)
            ->filterAggregateIntegerColumn('total_query', $request->total)
            ->filterByItem($request->item_brand, $request->item_name, $request->item_sku)
            ->filterDateField('created_at', $request->submitted)
            ->byUser($request->user_id)
                                              ->sortOn($request->sort, $request->order)
                                              ->searchFor($request->search)
                                              ->with(['lineItems', 'vendor', 'vendorAddress', 'vendorBankAccount', 'user', 'billingAddress', 'shippingAddress'])
                                              ->paginateWithAggregate($request->per_page, $request->page);
    }

    /**
     * Fetches the view that lets Users
     * submit POs.
     *
     * @return mixed
     */
    public function getSubmitForm()
    {
        if (Gate::allows('po_submit')) {
            $breadcrumbs = [
                ['<i class="fa fa-shopping-basket"></i> Purchase Orders', '/purchase_orders'],
                ['Submit', '#']
            ];
            return view('purchase_orders.submit', ['breadcrumbs' => $breadcrumbs]);
        }
        return redirect(route('showAllPurchaseOrders'));
    }

    /**
     * Handle POST req. from form to submit a new Purchase Order. We create
     * several models here: PurchaseOrder, Address, LineItem. This seems
     * sub-optimal. TODO ::: possible re-write.
     *
     * @param SubmitPurchaseOrderRequest $request
     * @return static
     */
    public function apiPostSubmit(SubmitPurchaseOrderRequest $request)
    {

        // Create our purchase orders
        $purchaseOrder = PurchaseOrder::create([
            'vendor_id' => $request->input('vendor_id'),
            'vendor_address_id' => $request->input('vendor_address_id'),
            'vendor_bank_account_id' => $request->input('vendor_bank_account_id'),
            'currency_id' => $request->input('currency_id'),
            'user_id' => Auth::user()->id,
            'company_id' => Auth::user()->company_id
        ]);


        // IF billing was not same as company
        $billingAddress = Auth::user()->company->address;
        if (!$request->input('billing_address_same_as_company')) {
            $billingAddress = Address::create([
                'contact_person' => $request->input('billing_contact_person'),
                'phone' => $request->input('billing_phone'),
                'address_1' => $request->input('billing_address_1'),
                'address_2' => $request->input('billing_address_2'),
                'city' => $request->input('billing_city'),
                'zip' => $request->input('billing_zip'),
                'state' => $request->input('billing_state'),
                'country_id' => $request->input('billing_country_id')
            ]);
        }

        $shippingAddress = $billingAddress;
        if (!$request->input('shipping_address_same_as_billing')) {
            $shippingAddress = Address::create([
                'contact_person' => $request->input('shipping_contact_person'),
                'phone' => $request->input('shipping_phone'),
                'address_1' => $request->input('shipping_address_1'),
                'address_2' => $request->input('shipping_address_2'),
                'city' => $request->input('shipping_city'),
                'zip' => $request->input('shipping_zip'),
                'state' => $request->input('shipping_state'),
                'country_id' => $request->input('shipping_country_id')
            ]);
        }

        // Create Line Items
        foreach ($request->input('line_items') as $lineItem) {
            $purchaseOrder->lineItems()->create([
                'quantity' => $lineItem['order_quantity'],
                'price' => $lineItem['order_price'],
                'payable' => array_key_exists('order_payable', $lineItem) ? $lineItem['order_payable'] : null,
                'delivery' => array_key_exists('order_delivery', $lineItem) ? $lineItem['order_delivery'] : null,
                'purchase_request_id' => $lineItem['id']
            ]);
        }


        // IF any Additional Costs - add them
        if ($additionalCosts = $request->input('additional_costs')) {
            foreach ($additionalCosts as $cost) {
                $purchaseOrder->additionalCosts()->create([
                    'name' => $cost['name'],
                    'type' => $cost['type'],
                    'amount' => $cost['amount']
                ]);
            }
        }


        // Process our PO
        $purchaseOrder->attachBillingAndShippingAddresses($billingAddress, $shippingAddress)// Attach addresses
                      ->updatePurchaseRequests()// Update Purchase Requests
                      ->attachRules()// Attach rules to Purchase Orders
                      ->tryAutoApprove();                                                       // Try to approve

        return $purchaseOrder;
    }

//
//    public function single(PurchaseOrder $purchaseOrder)
//    {
//        return view('purchase_orders.single', compact('purchaseOrder'));
//    }
//
//    public function approve(ApprovePurchaseOrderRequest $request)
//    {
//        PurchaseOrder::find($request->input('purchase_order_id'))->markApproved();
//        return redirect(route('showAllPurchaseOrders'));
//    }
//
//    public function reject(ApprovePurchaseOrderRequest $request)
//    {
//        PurchaseOrder::find($request->input('purchase_order_id'))->markRejected();
//        return redirect(route('showAllPurchaseOrders'));
//    }


}
