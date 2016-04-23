<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddNewVendorRequest;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class VendorsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('company');
    }


    public function getAll()
    {
        $breadcrumbs = [
            ['<i class="fa fa-truck"></i> Vendors', '#']
        ];

        $vendors = Auth::user()->company->vendors;
        return view('vendors.all', compact('vendors', 'breadcrumbs'));
    }

    public function postAddNewVendor(AddNewVendorRequest $request)
    {

    }
}
