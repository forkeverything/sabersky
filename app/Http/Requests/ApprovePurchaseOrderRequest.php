<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\PurchaseOrder;
use Illuminate\Support\Facades\Gate;

class ApprovePurchaseOrderRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $PO = PurchaseOrder::find($this->input('purchase_order_id'));
        return Gate::allows('approve', $PO);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'purchase_order_id' => 'required'
        ];
    }
}
