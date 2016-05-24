<?php

namespace App\Http\Controllers;

use App\Address;
use App\Http\Requests\AddNoteRequest;
use App\Http\Requests\ApprovePurchaseOrderRequest;
use App\Http\Requests\SaveLineItemRequest;
use App\Http\Requests\SubmitPurchaseOrderRequest;
use App\LineItem;
use App\Note;
use App\PurchaseOrder;
use App\Factories\PurchaseOrderFactory;
use App\PurchaseRequest;
use App\Repositories\CompanyPurchaseOrdersRepository;
use App\Rule;
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
            $purchaseOrder = $purchaseOrder->load('vendor', 'vendorAddress', 'vendorBankAccount', 'user', 'lineItems', 'lineItems.purchaseRequest.item', 'rules', 'billingAddress', 'shippingAddress', 'additionalCosts');
            $breadcrumbs = [
                ['<i class="fa fa-shopping-basket"></i> Purchase Orders', '/purchase_orders'],
                ['#' . $purchaseOrder->number, '#']
            ];
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
            return $purchaseOrder->load('vendor', 'vendorAddress', 'vendorBankAccount', 'user', 'lineItems', 'lineItems.purchaseRequest.item', 'rules', 'billingAddress', 'shippingAddress', 'additionalCosts');
        }
        return response("Not allowed to view that order", 403);
    }


    /**
     * Get Req. to approve / reject a Rule on a PO
     *
     * @param PurchaseOrder $purchaseOrder
     * @param Rule $rule
     * @param $action
     * @return string
     */
    public function getHandleRule(PurchaseOrder $purchaseOrder, Rule $rule, $action)
    {
        if($action !== 'reject' && $action !== 'approve') return response("Rules can only be approved or rejected.", 500);
        if (Gate::allows('view', $purchaseOrder)) {
            if($purchaseOrder->handleRule($action, $rule, Auth::user())) return $purchaseOrder;
            return response("Could not check that rule", 500);
        }
        return response("Not allowed to view that order", 403);
    }

    /**
     * Req. to mark a specific line item as paid
     * 
     * @param PurchaseOrder $purchaseOrder
     * @param LineItem $lineItem
     * @return LineItem
     */
    public function getMarkLineItemPaid(PurchaseOrder $purchaseOrder, LineItem $lineItem)
    {
        if (Gate::allows('view', $purchaseOrder) && Auth::user()->can('po_payments') && $purchaseOrder->approved) {
            if($lineItem->markPaid()) return 1;;
            return response("Could not mark line item as paid");
        }
        return response("Can't change that Line Item", 403);
    }

    public function getMarkLineItemReceived(PurchaseOrder $purchaseOrder, LineItem $lineItem, $status)
    {
        if (Gate::allows('view', $purchaseOrder) && Auth::user()->can('po_payments') && $purchaseOrder->approved) {
            if($lineItem->markReceived($status)) return $lineItem;
            return response("Could not mark line item as delivered");
        }
        return response("Can't change that Line Item", 403);
    }

    /**
     * Get all the Notes for a specific Order
     *
     * @param PurchaseOrder $purchaseOrder
     * @return mixed
     */
    public function getNotes(PurchaseOrder $purchaseOrder)
    {
        $this->authorize('view', $purchaseOrder);
        return $purchaseOrder->notes()->latest();
    }

    /**
     * Post to save a Note to a Order
     * @param PurchaseOrder $purchaseOrder
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function postAddNote(PurchaseOrder $purchaseOrder, AddNoteRequest $request)
    {
        $this->authorize('view', $purchaseOrder);
        return $purchaseOrder->addNote($request->input('content'), Auth::user());
    }

    /**
     * Deletes a Note attached to a Purchase Order
     *
     * @param PurchaseOrder $purchaseOrder
     * @param Note $note
     * @return bool|null
     * @throws \Exception
     */
    public function deleteNote(PurchaseOrder $purchaseOrder, Note $note)
    {
        $this->authorize('view', $purchaseOrder);
        $this->authorize('delete', $note);
        if($note->delete())return response("Deleted a note");
        return response("Could not delete note", 500);
    }


}
