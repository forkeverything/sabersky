<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Requests\SaveCompanyRequest;
use App\Permission;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function registerCompany()
    {
        if(Auth::user()->company) {
            return redirect('/dashboard');
        }
        return view('company.register');
    }

    public function saveCompany(SaveCompanyRequest $request)
    {
        $company = Company::create($request->all());
        $company->employees()->save($user = Auth::user());

        $role = $company->createAdmin();

        $role->giveAdminPermissions();

        $user->setRole($role);

        return redirect('/dashboard');
    }
}
