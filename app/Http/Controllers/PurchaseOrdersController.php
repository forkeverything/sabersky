<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApprovePurchaseOrderRequest;
use App\Http\Requests\POStep1Request;
use App\Http\Requests\POStep2Request;
use App\Http\Requests\SaveLineItemRequest;
use App\LineItem;
use App\PurchaseOrder;
use App\PurchaseRequest;
use App\User;
use App\Vendor;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PurchaseOrdersController extends Controller
{
    protected $existingPO;

    public function __construct()
    {
        $this->existingPO = Auth::user()->purchaseOrders()->whereSubmitted(0)->first();
    }

    public function all()
    {
        return view('purchase_orders.all');
    }

    public function apiAll(Request $request)
    {
        if ($request->ajax()) {
            return $purchaseOrders = Auth::user()->company->purchaseOrders()->whereSubmitted(1)->with(['project', 'vendor', 'user', 'lineItems', 'lineItems.purchaseRequest.item'])->get();
        }
        return redirect('/');
    }

    public function submit()
    {
        if (Gate::allows('po_submit')) {
            return view('purchase_orders.submit', ['existingPO' => $this->existingPO]);
        }
        return redirect(route('showAllPurchaseOrders'));
    }

    public function step1(POStep1Request $request)
    {
        $this->clearUnfinished(Auth::user());
        Auth::user()->purchaseOrders()->create($request->all());
        return redirect(route('submitPurchaseOrder'));
    }

    public function step2(POStep2Request $request)
    {
        if ($vendorId = $request->input('vendor_id')) {
            $this->existingPO->update([
                'vendor_id' => $vendorId
            ]);
        } else {
            $vendor = Vendor::create(array_merge($request->all(), ['company_id' => Auth::user()->company_id]));
            $this->existingPO->update([
                'vendor_id' => $vendor->id
            ]);
        }
        return redirect(route('submitPurchaseOrder'));
    }

    protected function clearUnfinished(User $user)
    {
        if ($unfinishedPOs = $user->purchaseOrders()->whereSubmitted(0)) {
            foreach ($unfinishedPOs as $unfinishedPO) {
                $unfinishedPO->delete();
            }
        }
        return true;
    }

    public function addLineItem()
    {
        if (Gate::allows('po_submit') && $this->existingPO) {
            return view('purchase_orders.add_line_item');
        } else {
            return redirect(route('showAllPurchaseOrders'));
        }
    }

    public function saveLineItem(SaveLineItemRequest $request)
    {
        if ($request->ajax()) {
            $this->existingPO->total += ($request->input('price') * $request->input('quantity'));
            $this->existingPO->save();
            return $this->existingPO->lineItems()->create($request->all());
        }
        abort(403, 'Wrong way go back.');
    }

    public function removeLineItem(LineItem $lineItem)
    {
        if (Gate::allows('po_submit')) {
            $this->existingPO->total -= ($lineItem->price * $lineItem->quantity);
            $this->existingPO->save();
            $lineItem->delete();
            return redirect()->back();
        }
        return redirect(route('showAllPurchaseOrders'));
    }

    public function cancelUnsubmitted()
    {
        if ($this->existingPO) {
            foreach ($this->existingPO->lineItems as $lineItem) {
                $lineItem->delete();
            }
            $this->existingPO->delete();
            return redirect(route('submitPurchaseOrder'));
        }
        return redirect(route('showAllPurchaseOrders'));
    }

    public function complete()
    {
        if (Gate::allows('po_submit')) {
            $this->existingPO->processSubmission();
            return redirect(route('showAllPurchaseOrders'));
        } else {
            abort(402, 'Forbidden Kingdom');
        }
    }

    public function single(PurchaseOrder $purchaseOrder)
    {
        return view('purchase_orders.single', compact('purchaseOrder'));
    }

    public function approve(ApprovePurchaseOrderRequest $request)
    {
        PurchaseOrder::find($request->input('purchase_order_id'))->markApproved();
        return redirect(route('showAllPurchaseOrders'));
    }

    public function reject(ApprovePurchaseOrderRequest $request)
    {
        PurchaseOrder::find($request->input('purchase_order_id'))->markRejected();
        return redirect(route('showAllPurchaseOrders'));
    }

}
