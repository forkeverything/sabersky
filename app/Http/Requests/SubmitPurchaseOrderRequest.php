<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Gate;

class SubmitPurchaseOrderRequest extends Request
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
            // Vendor
            'vendor_id' => 'required',
            'vendor_address_id' => 'required_if:po_requires_address, 1',
            'vendor_bank_account_id' => 'required_if:po_requires_bank_account, 1',
            // Order
                // Currency
                'currency_id' => 'required',
                // Billing
                'billing_phone' => 'required_if:billing_address_same_as_company, 0',
                'billing_address_1' => 'required_if:billing_address_same_as_company, 0',
                'billing_city' => 'required_if:billing_address_same_as_company, 0',
                'billing_zip' => 'required_if:billing_address_same_as_company, 0',
                'billing_state' => 'required_if:billing_address_same_as_company, 0',
                'billing_country_id' => 'required_if:billing_address_same_as_company, 0',
                // Shipping
                'shipping_phone' => 'required_if:shipping_address_same_as_billing, 0',
                'shipping_address_1' => 'required_if:shipping_address_same_as_billing, 0',
                'shipping_city' => 'required_if:shipping_address_same_as_billing, 0',
                'shipping_zip' => 'required_if:shipping_address_same_as_billing, 0',
                'shipping_state' => 'required_if:shipping_address_same_as_billing, 0',
                'shipping_country_id' => 'required_if:shipping_address_same_as_billing, 0',
            // Line Items
                'line_items.*.order_quantity' => 'required',
                'line_items.*.order_price' => 'required',
                'line_items.*' => 'required|line_item_quantity|line_item_price|pr_can_fulfill|pr_state_open',
            // Additional costs
                'additional_costs.*.name' => 'required',
                'additional_costs.*.type' => 'required',
                'additional_costs.*.amount' => 'required',
        ];
    }

    public function messages()
    {
        return [
            // Vendor
            'vendor_id.required' => 'No vendor was selected',
            'vendor_address_id.required_if' => 'No vendor address',
            'vendor_bank_account_id.required_if' => 'No vendor bank account',
            // Order
            // Currency
            'currency_id.required' => 'Currency not selected',
            // Billing
            'billing_phone.required_if' => 'Billing Phone required',
            'billing_address_1.required_if' => 'Billing Address required',
            'billing_city.required_if' => 'Billing City required',
            'billing_zip.required_if' => 'Billing Zip / Post Code required',
            'billing_state.required_if' => 'Billing State required',
            'billing_country_id.required_if' => 'Billing Country not selected',
            // Shipping
            'shipping_phone.required_if' => 'Shipping Phone required',
            'shipping_address_1.required_if' => 'Shipping Address required',
            'shipping_city.required_if' => 'Shipping City required',
            'shipping_zip.required_if' => 'Shipping Zip / Post Code required',
            'shipping_state.required_if' => 'Shipping State required',
            'shipping_country_id.required_if' => 'Shipping Country not selected',
            // Line Items
            'line_items.*.line_item_quantity' => 'Line Item QTY must be greater than 0 and less than requested amount',
            'line_items.*.line_item_price' => 'Line Items for a single item must have the same price',
            'line_items.*.pr_can_fulfill' => 'Not authorized to fulfill this Request',
            'line_items.*.pr_state_open' => 'Request is not open for fulfillment',
            'line_items.*.order_quantity.required' => 'Line Item QTY required',
            'line_items.*.order_price.required' => 'Line Item Price required',
            'line_items.*.required' => 'Line Items are required',
            // Additional costs
            'additional_costs.*.name.required' => 'Cost / Discount Name required',
            'additional_costs.*.type.required' => 'Cost / Discount Type required',
            'additional_costs.*.amount.required' => 'Cost / Discount Amount required',
        ];
    }
}
