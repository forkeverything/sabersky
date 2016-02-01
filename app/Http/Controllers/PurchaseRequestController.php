<?php

namespace App\Http\Controllers;

use App\Http\Requests\MakePurchaseRequestRequest;
use App\Project;
use App\PurchaseRequest;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PurchaseRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function all()
    {
        $purchaseRequests = Auth::user()->company->purchaseRequests;
        return view('purchase_requests.all', compact('purchaseRequests'));
    }

    public function make()
    {
        if(Auth::user()->is('director') || Auth::user()->is('planner')){
            return view('purchase_requests.make');
        }
        return redirect(route('showAllPurchaseRequests'));
    }

    public function save(MakePurchaseRequestRequest $request)
    {

        $project = Project::findOrFail($request->input('project_id'));
        $item = $project->saveItem($request);
        PurchaseRequest::create(
            array_merge($request->all(), ['item_id' => $item->id])
        );



        return redirect(route('showAllPurchaseRequests'));
    }


}
