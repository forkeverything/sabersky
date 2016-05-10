<?php

namespace App\Http\Controllers;

use App\Address;
use App\Http\Requests\AddAddressRequest;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Gate;

class AddressesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('company');
        $this->middleware('api.only');
    }

    /**
     * POST request to add a new Address
     *
     * @param AddAddressRequest $request
     * @return static
     */
    public function apiPostAddNew(AddAddressRequest $request)
    {
        return Address::create($request->all());
    }

    /**
     * PUT request to set an address at a route as
     * the Primary address for it's parent model
     *
     * @param Address $address
     * @return bool
     */
    public function apiPutSetPrimary(Address $address)
    {
        if (Gate::allows('edit', $address)) {
            $address->setAsPrimary();
            return response("Set address as primary", 200);
        }
        return response("Can't edit that address", 403);
    }

    /**
     * Delete an Address model
     *
     * @param Address $address
     * @return bool|null
     * @throws \Exception
     */
    public function apiDeleteAddress(Address $address)
    {
        if (Gate::allows('edit', $address)) {
            $address->delete();
            return response("Removed address", 200);
        }
        return response("Can't remove that address", 403);
    }
}
