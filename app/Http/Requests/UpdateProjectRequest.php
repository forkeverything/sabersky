<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UpdateProjectRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $project = Project::find($this->route('project'));
        return Gate::allows('view', $project);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:projects,name,' . Auth::user()->company_id,
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
