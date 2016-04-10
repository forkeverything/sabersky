<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class AddItemRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;    // Any body can add an item to a company
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sku' => 'unique:items,sku,NULL,id,company_id,' . Auth::user()->company->id,
            'name' => 'required|unique:items,name,NULL,id,company_id,' . Auth::user()->company->id . ',brand,' . $this->input('brand'),
            'specification' => 'required'
        ];
    }

    public function message()
    {
        return [
            'sku.unique' => 'SKU already exists',
            'name.required' => 'Item name is empty',
            'name.unique' => 'Item already exists',
            'specification.required' => 'Specification is empty'
        ];
    }
}
