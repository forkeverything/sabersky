<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Gate;

class SaveLineItemRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('po_submit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'purchase_request_id' => 'required|integer',
            'quantity' => 'required|integer',
            'price' => 'required|integer',
            'payable' => 'required|date',
            'delivery' => 'required|date'
        ];
    }
}
