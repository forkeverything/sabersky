<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Gate;

class AddStaffRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('team_manage');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'role_id' => 'required'
        ];
    }
    
    public function messages()
    {
        return [
            'name.required' => 'Name is needed',
            'email.required' => 'Email is needed',
            'email.unique' => 'Email address already taken',
            'role_id.required' => 'Role is not selected'
        ];
    }
}
