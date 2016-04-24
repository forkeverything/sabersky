<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class LinkCompanyToVendorRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('vendor_manage');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'linked_company_id' => 'required|unique:vendors,linked_company_id,NULL,id,base_company_id,' . Auth::user()->company->id
        ];
    }

    public function messages()
    {
        return [
            'linked_company_id.required' => 'No Company was selected to be linked',
            'linked_company_id.unique' => 'Company already linked as a Vendor'
        ];
    }
}
