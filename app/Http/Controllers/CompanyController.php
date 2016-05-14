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
use Illuminate\Support\Facades\Gate;

class CompanyController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', [
            'except' => ['postRegisterCompany','getPublicProfile']
        ]);
        $this->middleware('api.only', [
            'only' => ['apiGetOwn', 'apiGetCurrency', 'apiGetPublicProfile', 'apiGetSearchCompany']
        ]);
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
    public function apiGetOwn()
    {
        if($company = Auth::user()->company) return $company;
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
        if(Gate::allows('settings_change')){
            $company = Auth::user()->company;
            $company->update([
                'name' => $request->input('name'),
                'description' => $request->input('description')
            ]);
            $company->settings()->update([
                'currency_id' => $request->input('currency_id'),
                'currency_decimal_points' => $request->input('currency_decimal_points')
            ]);
            return response("Updated User Company", 200);
        }
        return response('Not authorized to change settings', 500);
    }

    /**
     * Fetches a Company's Public info available for
     * anyone to access.
     *
     * @param $term
     * @return mixed
     */
    public function apiGetPublicProfile($term)
    {
        return Company::fetchPublicProfile($term);
    }


    /**
     * Performs a DB look-up for a Company
     *
     * @param $query
     * @return mixed
     */
    public function apiGetSearchCompany($query)
    {
        if ($query) {
            $companies = Company::where('id', '!=', Auth::user()->company->id)
            ->where('name', 'LIKE', '%' . $query . '%')
            ->with('address');
            /*
             * TODO ::: Add ability for more search parameters: address, industry etc.
             */
            return $companies->take(10)->get();
        }
        return response("No search term given", 500);
    }

}
