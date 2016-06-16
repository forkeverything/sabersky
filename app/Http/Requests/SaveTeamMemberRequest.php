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
            'existing_user_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'existing_user_id.required' => 'No staff member was selected',
        ];
    }
}
