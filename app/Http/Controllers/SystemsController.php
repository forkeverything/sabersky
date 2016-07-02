<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;

use App\Http\Requests;

class SystemsController extends Controller
{

    /**
     * Status overview page of app-system
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getStatus()
    {
        $companyCount = Company::all()->count();
        return view('system.status', compact('companyCount'));
    }
}
