<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SaveTeamMemberRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
       return Gate::allows('team_manage') || Gate::allows('buyer_manage');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'existing_user_id' => 'required_without_all:name,email,role_id',
            'name' => 'required_with:email,role_id',
            'email' => 'required_with:name,role_id|unique:users,email',
            'role_id' => 'required_with:name, email'
        ];
    }

    public function messages()
    {
        return [
            'existing_user_id.required_without_all' => 'Please select an existing user or add a new one.',
            'name.required_with' => 'Name is required.',
            'email.required_with' => 'Email is required.',
            'email.unique' => 'That user has already joined.',
            'role_id.required_with' => 'New team members need a role.'
        ];
    }
}
