<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Gate;

class POStep2Request extends Request
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
            'vendor_id' => 'required_without_all:name,phone,address,bank_account_name,bank_account_number,bank_name',
            'name' => 'required_with:phone,address,bank_account_name,bank_account_number,bank_name',
            'phone' => 'required_with:name,address,bank_account_name,bank_account_number,bank_name',
            'address' => 'required_with:name,phone,bank_account_name,bank_account_number,bank_name',
            'bank_account_name' => 'required_with:name,phone,address,bank_account_number,bank_name',
            'bank_account_number' => 'required_with:name,phone,address,bank_account_name,bank_name',
            'bank_name' => 'required_with:name,phone,address,bank_account_name,bank_account_number',
        ];
    }
}
