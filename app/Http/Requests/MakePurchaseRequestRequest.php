<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
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
        return Gate::allows('pr_make');
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
            'item_id' => 'integer|required_without_all:name, specification',
            'name' => 'required_with:specification',
            'specification' => 'required_with:name',
            'quantity' => 'required|integer',
            'due' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'project_id.required' => 'Project is required',
            'item_id.required_without_all' => 'Please select an item or enter New Item details',
            'name.required_with' => 'New Item: Name is needed',
            'specification.required_with' => 'New Item: Detailed Specification is needed',
            'quantity.required' => 'Please enter how much of the item is required',
            'due.required' => 'Enter an approximate date the Item is needed on site'
        ];
    }
}
