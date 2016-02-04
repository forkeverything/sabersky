<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Gate;

class SaveSettingsRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('settings_change');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'po_high_max' => 'required|numeric|min:0',
            'po_med_max' => 'required|numeric|min:0',
            'item_md_max' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'po_high_max.required' => 'PO threshold cannot be blank',
            'po_high_max.numeric' => 'PO threshold accepts numbers only',
            'po_high_max.min' => 'PO threshold cannot be less than 0',
            'po_med_max.required' => 'PO threshold cannot be blank',
            'po_med_max.numeric' => 'PO threshold accepts numbers only',
            'po_med_max.min' => 'PO threshold cannot be less than 0',
            'item_md_max.required' => 'Maximum allowed Item Mean Difference is required',
            'item_md_max' => 'Mean Difference must be numbers only'
        ];
    }
}
