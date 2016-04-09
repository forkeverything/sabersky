<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Project;
use Illuminate\Support\Facades\Gate;

class MakePurchaseRequestRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(! $project = Project::find($this->input('project_id'))) return redirect()->back();
        // User must have permission to make PR's & Project must belong to user's company
        return Gate::allows('pr_make') && Gate::allows('view', $project);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'project_id' => 'required|integer',
            'item_id' => 'required_without_all:name, specification|integer',
            'name' => 'required_with:specification',
            'specification' => 'required_with:name',
            'quantity' => 'required|integer',
            'due' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'project_id.required' => 'No Project selected',
            'item_id.required_without_all' => 'No Item selected',
            'name.required_with' => 'New Item: Name is needed',
            'specification.required_with' => 'New Item: Detailed Specification is needed',
            'quantity.required' => 'Quantity to purchase not set',
            'due.required' => 'Due Date was not set'
        ];
    }
}
