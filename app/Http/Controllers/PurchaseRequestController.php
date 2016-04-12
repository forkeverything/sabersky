<?php

namespace App\Http\Controllers;

use App\Http\Requests\CancelPurchaseRequestRequest;
use App\Http\Requests\MakePurchaseRequestRequest;
use App\Item;
use App\Project;
use App\PurchaseRequest;
use App\Repositories\CompanyPurchaseRequestsRepository;
use App\Utilities\CompanyPurchaseRequests;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class PurchaseRequestController extends Controller
{
    protected $purchaseRequests;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('company');
        if ($user = Auth::user()) {
            $this->purchaseRequests = $user->company->purchaseRequests->load(['project', 'item', 'user']);
        }
    }

    /**
     * Handle GET request to view all
     * relevant purchase requests.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAll()
    {
        $breadcrumbs = [
            ['<i class="fa fa-shopping-basket"></i> Purchase Requests', '#']
        ];
        $purchaseRequests = Auth::user()->company->purchaseRequests()->paginate(15);
        return view('purchase_requests.all', compact('breadcrumbs', 'purchaseRequests'));
    }

    /**
     * GET All PRs that belongs to the Users
     * Company
     *
     * @param Request $request
     * @return mixed
     */
    public function apiGetAll(Request $request)
    {

        if ($request->ajax()) {
            $filter = $request->query('filter');
            $sort = $request->query('sort');
            $order = $request->query('order');
            $urgent = $request->query('urgent');
            $perPage = $request->query('per_page');



            $data = CompanyPurchaseRequestsRepository::forCompany(Auth::user()->company)
                                                     ->filterBy($filter)
                                                     ->sortOn($sort, $order)
                                                     ->onlyUrgent($urgent)
                                                     ->paginate($perPage);

            return $data;
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
            $breadcrumbs = [
                ['<i class="fa fa-shopping-basket"></i> Purchase Requests', '/purchase_requests'],
                ['Make', '#']
            ];
            return view('purchase_requests.make', compact('breadcrumbs'));
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
    public function postMakePR(MakePurchaseRequestRequest $request)
    {
        // Find Project
        $project = Project::findOrFail($request->input('project_id'));
        // Find Item
        $item = Item::findOrFail($request->input('item_id'));
        // Attach Item to Project
        $project->saveItem($item);

        // Create the Purchase Request
        if (PurchaseRequest::make($request, Auth::user())) return response("Made a new Purchase Request", 200);
        return response("Could not make Purchase Request", 500);

    }

    /**
     * Fetches PR by id and loads view
     * for a Single Purchase Request
     *
     * @param PurchaseRequest $purchaseRequest
     * @return mixed
     */
    public function getSingle(PurchaseRequest $purchaseRequest)
    {
        $purchaseRequest = $purchaseRequest->load('item', 'project');
        return view('purchase_requests.single', compact('purchaseRequest'));
    }

    /**
     * Handles GET request from API for
     * all available PRs
     *
     * @return mixed
     */
    public function apiGetAvailable()
    {
        if (($unfinishedPO = Auth::user()->purchaseOrders()->whereSubmitted(0)->first()) && Gate::allows('po_submit')) {
            $addedPRIds = $unfinishedPO->lineItems->pluck('purchase_request_id')->toArray();
            return $this->purchaseRequests->where('project_id', $unfinishedPO->project_id)->where('state', 'open')->reject(function ($item) use ($addedPRIds) {
                return in_array($item->id, $addedPRIds) || $item->quantity <= 0;
            })->load('item.photos');
        }
        abort(403, 'No created but unsubmitted purchase order or not allowed to submit purchase order');

        /*
         * TODO :: Find a better way to create PO / Line items.
         * Then, refactor finding available PR to add to Line Item.
         * The current way of caching using DB is not very clean.
         */
    }

    /**
     * POST request to cancel a PR
     *
     * @param CancelPurchaseRequestRequest $request
     * @return mixed
     */
    public function postCancel(CancelPurchaseRequestRequest $request)
    {
        PurchaseRequest::find($request->input('purchase_request_id'))
                       ->cancel();
        return redirect(route('showAllPurchaseRequests'));
    }


}
