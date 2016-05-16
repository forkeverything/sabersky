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
use App\Factories\PurchaseOrderFactory;
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
            'only' => ['apiGetAll', 'apiPostSubmit', 'apiGetSingle']
        ]);

        // Call base Controller class's constructor to get access to shared view $variables
        parent::__construct();
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
                                              ->withCurrency($request->currency_id)
                                              ->filterIntegerField('total', $request->total)
                                              ->filterByItem($request->item_brand, $request->item_name, $request->item_sku)
                                              ->filterDateField('purchase_orders.created_at', $request->submitted)
                                              ->byUser($request->user_id)
                                              ->sortOn($request->sort, $request->order)
                                              ->searchFor($request->search)
                                              ->with(['lineItems', 'vendor', 'vendorAddress', 'vendorBankAccount', 'user', 'billingAddress', 'shippingAddress'])
                                              ->paginate($request->per_page);

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
     * Handle POST req. from form to submit a new Purchase Order.
     *
     * @param SubmitPurchaseOrderRequest $request
     * @return static
     */
    public function apiPostSubmit(SubmitPurchaseOrderRequest $request)
    {
        return PurchaseOrderFactory::make($request, Auth::user());
    }


    /**
     * Show the single Purchase Order view
     *
     * @param PurchaseOrder $purchaseOrder
     * @return mixed
     */
    public function getSingle(PurchaseOrder $purchaseOrder)
    {
        if (Gate::allows('view', $purchaseOrder)) {
            $breadcrumbs = [
                ['<i class="fa fa-shopping-basket"></i> Purchase Orders', '/purchase_orders'],
                ['#' . $purchaseOrder->number, '#']
            ];
            $purchaseOrder = $purchaseOrder->load('vendor', 'user', 'lineItems', 'rules', 'billingAddress', 'shippingAddress');
            return view('purchase_orders.single', compact('purchaseOrder', 'breadcrumbs'));
        }
        flash()->error('Not allowed to view that Order');
        return redirect('/purchase_orders');
    }

    /**
     * Get req. to get a single PO as json
     *
     * @param PurchaseOrder $purchaseOrder
     * @return $this
     */
    public function apiGetSingle(PurchaseOrder $purchaseOrder)
    {
        if (Gate::allows('view', $purchaseOrder)) {
            return $purchaseOrder->load('vendor', 'vendorAddress', 'vendorBankAccount', 'user', 'lineItems','lineItems.purchaseRequest.item', 'rules', 'billingAddress', 'shippingAddress');
        }
        abort(403, "Not allowed to view that order");
    }

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
