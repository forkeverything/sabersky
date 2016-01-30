<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class SaveTeamMemberRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Auth::user()->is('director')) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'existing_user' => 'required_without_all:name,email',
            'name' => 'required_with:email',
            'email' => 'required_with:name|unique:users,email'
        ];
    }

    public function messages()
    {
        return [
            'existing_user.required_without_all' => 'Please select an existing user if you are not adding a new one.',
            'name.required_with' => 'Name is required.',
            'email.required_with' => 'Email is required.',
            'email.unique' => 'That user has already joined.'
        ];
    }
}
