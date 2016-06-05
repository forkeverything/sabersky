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

    }

    public function getHome(Request $request)
    {
        if ($user = Auth::user()) {
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
        return view('landing');
    }
}
