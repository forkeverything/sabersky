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
        // Static fields
        $rules = [
            'sku' => 'unique:items,sku,NULL,id,company_id,' . Auth::user()->company->id,
            'name' => 'required|unique:items,name,NULL,id,company_id,' . Auth::user()->company->id . ',brand,' . $this->input('brand'),
            'specification' => 'required',
            'product_subcategory_id' => 'required'
        ];

        // To handle item_photos array of files
        $nbr = count($this->input('item_photos')) - 1;  // How many files
        foreach(range(0, $nbr) as $index) {
            // Create rule  for each file dynamically
            $rules['item_photos.' . $index] = 'image|max:5000';
        }

        return $rules;
    }

    public function message()
    {
        return [
            'sku.unique' => 'SKU already exists',
            'name.required' => 'Item name is empty',
            'name.unique' => 'Item already exists',
            'specification.required' => 'Specification is empty',
            'product_subcategory_id.required' => 'Item category not selected'
        ];
    }
}
