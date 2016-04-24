<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Requests\AddNewVendorRequest;
use App\Http\Requests\LinkCompanyToVendorRequest;
use App\Vendor;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class VendorsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('company');
    }


    /**
     * show view for All vendors
     * @return mixed
     */
    public function getAll()
    {
        $breadcrumbs = [
            ['<i class="fa fa-truck"></i> Vendors', '#']
        ];

        $vendors = Auth::user()->company->vendors;
        
        return view('vendors.all', compact('vendors', 'breadcrumbs'));
    }

    /**
     * Shows Form for adding a New Vendor to the Company
     *
     * @return mixed
     */
    public function getAddForm()
    {
        if (Gate::allows('vendor_manage')) {
            $breadcrumbs = [
                ['<i class="fa fa-truck"></i> Vendors', '/vendors'],
                ['Add New', '#']
            ];
            return view('vendors.add', compact('breadcrumbs'));
        }
    }

    /**
     * Handle POST request to add a new Vendor
     * 
     * @param AddNewVendorRequest $request
     */
    public function postAddCustomVendor(AddNewVendorRequest $request)
    {
        $vendor = Auth::user()->company->vendors()->create([
            'name' => $request->input('name'),
            'description' => $request->input('description')
        ]);

        flash()->success('Created a new custom vendor');

        return redirect('/vendors/' . $vendor->id);
    }

    /**
     * POST request to link a Company to the an currently authenticated
     * User's Company's Vendor.
     *
     * @param LinkCompanyToVendorRequest $request
     * @return static
     */
    public function postLinkCompanyToVendor(LinkCompanyToVendorRequest $request)
    {
        $company = Company::find($request->input('linked_company_id'));
        // Did we get a vendor_id in the request?
        if ($vendor = Vendor::find($request->input('vendor_id'))) {
            // Link company to an existing vendor
            return $vendor->linkCompany($company);
        } else {
            // Create new vendor and link it to the company
            return Vendor::createAndLinkFromCompany(Auth::user()->company, $company);
        }
    }

    /**
     * Retrieves the view for a single Vendor if
     * the Client is authorized to view it.
     *
     * @param Vendor $vendor
     * @return mixed
     */
    public function getSingle(Vendor $vendor)
    {
        if(Gate::allows('view', $vendor)) {
            $breadcrumbs = [
                ['<i class="fa fa-truck"></i> Vendors', '/vendors'],
                [$vendor->name, '#']
            ];
            return view('vendors.single', compact('breadcrumbs', 'vendor'));
        };
        return redirect('/vendors');
    }

    /**
     * POST request to update a Vendor's description
     * 
     * @param Vendor $vendor
     * @param Request $request
     * @return mixed
     */
    public function postSaveDescription(Vendor $vendor, Request $request)
    {
        if (Gate::allows('edit', $vendor)) {
            if($vendor->update(['description' => $request->input('description')])) {
                return response("Updated vendor description", 200);
            };

            return response("Could not update vendor description", 500);
        }
        
        return response("Not authorized to edit that Vendor", 403);
            
    }
}
