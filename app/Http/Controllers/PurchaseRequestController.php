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

    public function all(Request $request)
    {

        $query = $this->queryStringFilter($request);

        $tableHeadings = [
            'due_date' => 'Due Date',
            'project' => 'Project',
            'item' => 'Item',
            'specification' => 'Specification',
            'quantity' => 'Quantity',
            'user' => 'Requested by',
            'time_requested' => 'Requested'
        ];

        $filterStates = [
            'open' => 'Open',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled'
        ];


        $purchaseRequests = PurchaseRequest::sortFilter(Auth::user(), $query['sort'], $query['order'], $query['filter'], $query['urgent']);

        $variables = array_merge($query, compact('purchaseRequests', 'tableHeadings', 'filterStates'));

        return view('purchase_requests.all', $variables);
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

    protected function queryStringFilter(Request $request)
    {
        $sort = $request->input('sort');
        $order = $request->input('order');
        $filter = $request->input('filter');
        $urgent = $request->input('urgent');

        $sort = ($sort === 'due_date' || $sort === 'project' || $sort === 'item' || $sort === 'quantity' || $sort === 'user' || $sort === 'time_requested') ? $sort: 'time_requested';
        $order = ($order === 'asc' || $order === 'desc') ? $order: 'asc';
        $filter = ($filter === 'open' || $filter === 'completed' || $filter === 'cancelled') ? $filter: 'open';
        $urgent = ($urgent == '1' || $urgent == '0') ? $urgent: 0;

        return compact('sort', 'order', 'filter', 'urgent');
    }



}
