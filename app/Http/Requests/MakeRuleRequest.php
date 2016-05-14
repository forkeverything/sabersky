<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class MakeRuleRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'rule_property_id' => 'required|integer',
            'rule_trigger_id' => 'required|integer',
            'limit' => 'required_if:has_limit,1|numeric',
            'currency_id' => 'required_if:has_currency,1|integer',
            'roles' => 'required|array'
        ];
    }
}
