<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveSettingsRequest;
use App\Permission;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
//        $this->middleware('billing', [
//            'except' => 'getBilling'
//        ]);
        $this->middleware('settings.change');
    }

    /**
     * GET Settings - Company view
     *
     * @return mixed
     */
    public function getCompany()
    {
        return view('settings.company', ['page' => 'company']);
    }

    /**
     * GET Settings - Roles View
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getRoles()
    {
        $page = 'roles';
        $roles = Auth::user()->company->roles->load('users', 'permissions');
        $permissions = Permission::all(); // System-wide defined permissions, shared by all Users
        return view('settings.roles', compact('page', 'roles', 'permissions'));
    }

    /**
     * GET Settings - Purchasing View
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPurchasing()
    {
        $page = 'purchasing';
        $roles = Auth::user()->company->roles;
        $ruleProperties = getRuleProperties();
        $rules = Auth::user()->company->rules;
        return view('settings.purchasing', compact('page', 'roles', 'rules', 'ruleProperties'));
    }

    /**
     * Settings - Billing & Subscription page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getBilling()
    {
        $user = Auth::user();
        $page = 'billing';
        $subscribed = $user->company->subscribed('main');
        $plan = null;
        if($user->company->subscribedToPlan('growth', 'main')) $plan = 'growth';
        if($user->company->subscribedToPlan('enterprise', 'main')) $plan = 'enterprise';

        $numActiveStaff = $user->company->activeStaff->count();

        $subscription = $user->company->subscription;



        return view('settings.billing', compact('page', 'subscribed', 'plan', 'numActiveStaff', 'subscription'));
    }
}
