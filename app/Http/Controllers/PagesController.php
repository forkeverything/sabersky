<?php

namespace App\Http\Controllers;

use App\Company;
use App\Repositories\CompanyPurchaseOrdersRepository;
use App\Repositories\UserPurchaseRequestsRepository;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class PagesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'billing'], ['only' => 'getDashboard']);
    }

    /**
     * Home / Landing Page
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function getHome(Request $request)
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }
        return view('landing', ['fullPage' => true]);
    }

    /**
     * Dashboard for logged-in users
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDashboard()
    {
        $user = Auth::user();

        $user->load('projects');


        $variables = [
            'user' => $user,
            'numUnfulfilledRequests' => UserPurchaseRequestsRepository::forUser($user)->fulfillable()
                                                                      ->getWithoutQueryProperties()
                                                                      ->count(),
            'numApprovableOrders' => CompanyPurchaseOrdersRepository::forCompany($user->company)
                                                                    ->onlyWhereApprovableBy(1, $user)
                                                                    ->whereStatus('pending')
                                                                    ->getWithoutQueryProperties()
                                                                    ->count(),
            'numVendors' => $user->company->vendors->count(),
            'numItems' => $user->company->items->count(),
            'numRequests' => $user->company->purchaseRequests->count(),
            'numOrders' => $user->company->purchaseOrders->count(),
        ];

        return view('dashboard', $variables);
    }

}
