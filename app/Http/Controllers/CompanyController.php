<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Requests\SaveCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
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

    /**
     * GET request to register a company.
     * If a user already has one, redirect
     * him to dashboard.
     * 
     * @return mixed
     */
    public function registerCompany()
    {
        if(Auth::user()->company) {
            return redirect('/dashboard');
        }
        return view('company.register');
    }

    public function saveCompany(SaveCompanyRequest $request)
    {;
        $company = Company::create($request->all());
        $company->employees()->save($user = Auth::user());

        $role = $company->createAdmin();

        $role->giveAdminPermissions();

        $user->setRole($role);

        return redirect('/dashboard');
    }

    /**
     * Returns the Authenticated User's
     * company model.
     *
     * @return mixed
     */
    public function getOwn()
    {
        if($company = Auth::user()->company) return $company;
    }

    /**
     * Gets the company's set currency
     *
     * @return mixed
     */
    public function getCurrency()
    {
        if($company = Auth::user()->company) return $company->currency;
        return '$';
    }

    /**
     * PUT req. to update a user's company
     * information.
     * 
     * @param UpdateCompanyRequest $request
     * @return mixed
     */
    public function putUpdate(UpdateCompanyRequest $request)
    {
        if(Auth::user()->company()->update($request->all())){
            return response('Updated company info');
        }
        abort(400, 'Could not update company');
    }
}
