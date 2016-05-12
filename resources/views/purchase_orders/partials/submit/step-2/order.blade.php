<h3>Order</h3>
{{--<po-submit-order-details  :shipping-address-same-as-billing.sync="shippingAddressSameAsBilling" :shipping-address.sync="shippingAddress"></po-submit-order-details>--}}
<div class="currency-selection section">
    <h5 class="required">
        Currency
    </h5>
    <currency-selecter :name.sync="currency" :default="userCurrency"></currency-selecter>
</div>
<div class="billing-address section">
    <h5 class="required">Billing Address</h5>
    <po-billing-address :billing-address-same-as-company.sync="billingAddressSameAsCompany" :billing-address.sync="billingAddress" :company="company"></po-billing-address>
</div>
<div class="shipping-address section">
    <h5>Shipping Address</h5>
    <po-shipping-address :shipping-address-same-as-billing.sync="shippingAddressSameAsBilling" :shipping-address.sync="shippingAddress"></po-shipping-address>
</div>