<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\User;
use Illuminate\Support\Facades\Gate;

class ChangeUserRoleRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user =  User::find($this->route('user'));
        return Gate::allows('edit', $user) && Gate::allows('team_manage');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'role_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'role_id.required' => 'A new Role must be selected'
        ];
    }
}
