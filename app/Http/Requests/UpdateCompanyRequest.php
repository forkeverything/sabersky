<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UpdateCompanyRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // User has a company and is allowed to change settings
        return Auth::user()->company && Gate::allows('settings_change') ;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:companies,name,' . Auth::user()->company->id ,
            'description' => 'required',
            'currency' => 'required'
        ];
    }
}
