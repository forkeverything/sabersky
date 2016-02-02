<?php

namespace App\Http\Controllers;

use App\Http\Requests\POStep1Request;
use App\Http\Requests\POStep2Request;
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
        }
        return redirect(route('showAllPurchaseOrders'));
    }

}
