<?php

namespace App\Http\Controllers;

use App\Http\Requests\MakePurchaseRequestRequest;
use App\Project;
use App\PurchaseRequest;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PurchaseRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function all()
    {
        $purchaseRequests = PurchaseRequest::with(['project', 'item', 'user'])->get();
        return view('purchase_requests.all', compact('purchaseRequests'));
    }

    public function apiAll(Request $request)
    {
        if ($request->ajax()) {
            return PurchaseRequest::with(['project', 'item', 'user'])->get();
        } else {
            abort('501', 'Oops..can\'t get in that way.');
        }
    }

    public function make()
    {
        if(Gate::allows('pr_make')) {
            return view('purchase_requests.make');
        }
        return redirect(route('showAllPurchaseRequests'));
    }

    public function save(MakePurchaseRequestRequest $request)
    {

        $project = Project::findOrFail($request->input('project_id'));
        $item = $project->saveItem($request);
        PurchaseRequest::create(
            array_merge($request->all(), [
                'item_id' => $item->id,
                'user_id' => Auth::user()->id
            ])
        );
        return redirect(route('showAllPurchaseRequests'));
    }

    public function single(PurchaseRequest $purchaseRequest)
    {
        return view('purchase_requests.single', compact('purchaseRequest'));
    }

    public function available()
    {
        if(($unfinishedPO = Auth::user()->purchaseOrders()->whereSubmitted(0)->first()) && Gate::allows('po_submit')) {
            $addedPRIds = $unfinishedPO->lineItems->pluck('purchase_request_id');
            return PurchaseRequest::whereProjectId(Auth::user()->purchaseOrders()->whereSubmitted(0)->first()->project_id)->whereState('open')->with(['item', 'user'])->where('quantity','>',0)->whereNotIn('id', $addedPRIds)->get();
        }
    }



}
