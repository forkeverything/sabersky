<?php

namespace App\Http\Controllers;

use App\Http\Requests\MakePurchaseRequestRequest;
use App\Item;
use App\Project;
use App\PurchaseRequest;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PurchaseRequestController extends Controller
{
    protected $purchaseRequests;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('company');
        $this->purchaseRequests = Auth::user()->company->purchaseRequests->load(['project', 'item', 'user']);
    }

    /**
     * Handle GET request to view all
     * relevant purchase requests.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAll()
    {
        return view('purchase_requests.all')->with('purchaseRequests', $this->purchaseRequests);
    }

    /**
     * GET Purchase Requests in
     * JSON
     * @param Request $request
     * @return mixed
     */
    public function apiAll(Request $request)
    {
        if ($request->ajax()) {
            return $this->purchaseRequests;
        } else {
            abort('501', 'Oops..can\'t get in that way.');
        }
    }

    /**
     * Shows the form to make a
     * Purchase Request.
     *
     * @return mixed
     */
    public function getMakePRForm()
    {
        if (Gate::allows('pr_make')) {
            return view('purchase_requests.make');
        }
        return redirect(route('showAllPurchaseRequests'));
    }

    /**
     * POST request to save a new
     * Purchase Request.
     *
     * @param MakePurchaseRequestRequest $request
     * @return mixed
     */
    public function postSave(MakePurchaseRequestRequest $request)
    {
        // Find / Make an Item
        $item = Item::newFromPurchaseRequestRequest($request);
        // Handle files attached to Form
        $item->handleFiles($request->file('item_photos'));
        // Find Project
        $project = Project::findOrFail($request->input('project_id'));
        // Attach Item to Project
        $project->saveItem($item);
        // Create the Purchase Request
        PurchaseRequest::make($request, $item, Auth::user());
        
        return redirect(route('showAllPurchaseRequests'));
    }

    public function single(PurchaseRequest $purchaseRequest)
    {
        $purchaseRequest = $purchaseRequest->load('item', 'project');
        return view('purchase_requests.single', compact('purchaseRequest'));
    }

    public function available()
    {
        if (($unfinishedPO = Auth::user()->purchaseOrders()->whereSubmitted(0)->first()) && Gate::allows('po_submit')) {
            $addedPRIds = $unfinishedPO->lineItems->pluck('purchase_request_id')->toArray();
            return $this->purchaseRequests->where('project_id', $unfinishedPO->project_id)->where('state', 'open')->reject(function ($item) use ($addedPRIds) {
                return in_array($item->id, $addedPRIds) || $item->quantity <= 0;
            })->load('item.photos');
        }
        abort(403, 'No unsubmitted purchase order or not allowed to submit purchase order');
    }

    public function cancel(Request $request)
    {
        if (Gate::allows('pr_make')) {
            $purchaseRequest = PurchaseRequest::find($request->input('purchase_request_id'));
            if (Auth::user()->company_id == $purchaseRequest->project->company_id) {
                $purchaseRequest->state = 'cancelled';
                $purchaseRequest->save();
            }
        }
        return redirect(route('showAllPurchaseRequests'));
    }


}
