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
            'rule_property_id' => 'required|integer|rule_property',
            'rule_trigger_id' => 'required|integer|rule_trigger|rule_unique',
            'limit' => 'required_if:has_limit,1|numeric',
            'currency_id' => 'required_if:has_currency,1|integer',
            'roles' => 'required|array|rule_roles'
        ];
    }

    public function messages()
    {
        return [
           'rule_property_id.required' => 'Rule property cannot be empty',
            'rule_property_id.integer' => 'Invalid rule property',
            'rule_property_id.rule_property' => 'Invalid rule property',
            'rule_trigger_id.required' => 'Rule trigger cannot be empty',
            'rule_trigger_id.integer' => 'Invalid rule trigger',
            'rule_trigger_id.rule_trigger' => 'Invalid rule trigger',
            'rule_trigger_id.rule_unique' => 'Rule already exists',
            'limit.required_if' => 'That rule requires a limit',
            'limit.numeric' => 'Invalid limit',
            'currency_id.requird_if' => 'That rule requires a currency',
            'currency_id.integer' => 'Invalid Currency',
            'roles.required' => 'Need roles that can approve the rule',
            'roles.array' => 'Invalid roles',
            'roles.rule_roles' => 'Roles does not belong to company'
        ];
    }
}
