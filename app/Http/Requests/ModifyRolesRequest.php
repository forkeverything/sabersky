<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Role;
use Illuminate\Support\Facades\Auth;

class ModifyRolesRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $role = Role::findOrFail($this->input('role')['id']);
        return $role->company->name === Auth::user()->company->name;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
