<?php

namespace App\Http\Controllers;

use App\Http\Requests\POStep1Request;
use App\Http\Requests\POStep2Request;
use App\Http\Requests\SaveLineItemRequest;
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
        if($unfinishedPOs = $user->purchaseOrders()->whereSubmitted(0)) {
            foreach ($unfinishedPOs as $unfinishedPO) {
                $unfinishedPO->delete();
            }
        }
        return true;
    }

    public function addLineItem()
    {
        if (Gate::allows('po_submit')) {
            return view('purchase_orders.add_line_item');
        } else {
            return redirect(route('showAllPurchaseOrders'));
        }
    }

    public function saveLineItem(SaveLineItemRequest $request)
    {
        if ($request->ajax()) {
            return $this->existingPO->lineItems()->create($request->all());
        }
        abort(403, 'Wrong way go back.');
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
            $this->existingPO->submitted = true;
            $this->existingPO->save();
            return redirect(route('showAllPurchaseOrders'));
        } else {
            abort(402, 'Forbidden Kingdom');
        }
    }

}
