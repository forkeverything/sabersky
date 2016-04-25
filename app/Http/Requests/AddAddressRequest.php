<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Vendor;
use Illuminate\Support\Facades\Gate;

class AddAddressRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $canEditModel = false;
        $type = $this->input('owner_type');
        switch ($type) {
            case 'vendor':
                $canEditModel = Gate::allows('vendor_manage') && Gate::allows('edit', Vendor::find($this->input('owner_id')));
                break;
            case 'company':
                $canEditModel = Auth::user()->company_id === $this->input('owner_id');
                break;
        }
        return $canEditModel;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'owner_id' => 'required',
            'owner_type' => 'required',
            'address_1' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country_id' => 'required',
            'zip' => 'required',
            'phone' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'owner_id.required' => 'No Address Owner (id)',
            'owner_type.required' => 'No Address Owner (type)'
        ];
    }
}
