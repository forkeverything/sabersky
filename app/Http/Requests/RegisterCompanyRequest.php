<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class RegisterCompanyRequest extends Request
{
    /**
     * Anybody is authorized to create a company (and account)
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Fallback Server Validation. We're already
     * validating this front-end in the popup.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_name' => 'required|unique:companies,name',
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6'
        ];
    }
}
