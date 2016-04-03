<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class StartProjectRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
            return Gate::allows('project_manage');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:projects,name,NULL,id,company_id,' . Auth::user()->company_id,
            'location' => 'required',
            'description' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Project already exists',
            'location.required' => 'Address cannot be empty',
            'description' => 'Description cannot be empty'
        ];
    }
}
