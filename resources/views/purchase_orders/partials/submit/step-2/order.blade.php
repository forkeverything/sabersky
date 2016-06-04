<span class="card-title">Order</span>
{{--<po-submit-order-details  :shipping-address-same-as-billing.sync="shippingAddressSameAsBilling" :shipping-address.sync="shippingAddress"></po-submit-order-details>--}}
<div class="currency-selection part">
    <h4 class="required">
        Currency
    </h4>
    <company-currency-selecter :currency-object.sync="currency"  :currencies="availableCurrencies"></company-currency-selecter>
</div>
<div class="billing-address part">
    <h4 class="required">Billing Address</h4>
    <po-billing-address :billing-address-same-as-company.sync="billingAddressSameAsCompany" :billing-address.sync="billingAddress" :company="company"></po-billing-address>
</div>
<div class="shipping-address part">
    <h4>Shipping Address</h4>
    <po-shipping-address :shipping-address-same-as-billing.sync="shippingAddressSameAsBilling" :shipping-address.sync="shippingAddress"></po-shipping-address>
</div>