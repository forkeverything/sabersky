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
            'item_id' => 'required|integer',
            'quantity' => 'required|integer',
            'due' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'project_id.required' => 'No Project selected',
            'item_id.required' => 'No Item selected',
            'quantity.required' => 'Quantity required not given',
            'due.required' => 'Due Date was not set'
        ];
    }
}
