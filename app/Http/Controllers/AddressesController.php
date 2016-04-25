<?php

namespace App\Http\Controllers;

use App\Address;
use App\Http\Requests\AddAddressRequest;
use Illuminate\Http\Request;

use App\Http\Requests;

class AddressesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('company');
    }

    /**
     * POST request to add a new Address
     * 
     * @param AddAddressRequest $request
     * @return static
     */
    public function postAddNew(AddAddressRequest $request)
    {
        return Address::create($request->all());
    }
}
