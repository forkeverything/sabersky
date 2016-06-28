<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AddNewVendorRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Only Clients that are allowed to manage Vendors can add new Vendors
        return Gate::allows('vendor_manage');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $companyID = Auth::user()->company_id;
        return [
            'name' => 'required|unique:vendors,name,NULL,id,company_id,' . $companyID
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vendor name cannot be blank',
            'name.unique' => 'A vendor with that name already exists'
        ];
    }
}
