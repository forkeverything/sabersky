<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Requests\RegisterCompanyRequest;
use App\Http\Requests\SaveCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Permission;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', [
            'except' => ['postRegisterCompany','getPublicProfile']
        ]);
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
    {
        $company = Company::create($request->all());
        $company->employees()->save($user = Auth::user());

        $role = $company->createAdmin();

        $role->giveAdminPermissions();

        $user->setRole($role);

        return redirect('/dashboard');
    }

    /**
     * POST request to register a new Company.
     * Will create a company as well as a
     * user for the new company.
     * 
     * @param RegisterCompanyRequest $request
     * @return mixed
     */
    public function postRegisterCompany(RegisterCompanyRequest $request)
    {
        $company = Company::register($request->input('company_name'));
        $user = User::make($request->input('name'), $request->input('email'), $request->input('password'));
        $company->addEmployee($user);

        $adminRole = $company->createAdmin();
        $adminRole->giveAdminPermissions();

        $user->setRole($adminRole);

        Auth::logout();
        Auth::login($user);

        if(Auth::user())return response("Registered new Company", 200);

        return response("Could not register Company or User", 500);
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

    /**
     * Fetches a Company's Public info available for
     * anyone to access.
     *
     * @param $term
     * @return mixed
     */
    public function getPublicProfile($term)
    {
        return Company::fetchPublicProfile($term);
    }
}
