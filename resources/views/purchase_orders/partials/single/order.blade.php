<!-- PO Single - Order -->
<div class="meta hidden-xs">
    @include('purchase_orders.partials.single.meta')
</div>
<div class="purchaser-addresses">
    <h2 class="hidden-xs">Order</h2>
    <div class="row">
        <div class="col-sm-6">
            <div class="billing-address">
        <h4>
            Billing Address
                <small v-if="purchaseOrder.billing_address_same_as_company">(company)</small>
        </h4>
                <address :address="purchaseOrder.billing_address" :company="company"></address>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="shipping-address">
                <h4>Shipping Address</h4>
                    <em v-if="purchaseOrder.shipping_address_same_as_billing" class="display-block">Same as billing address</em>
                    <address v-else :address="purchaseOrder.shipping_address" :company="company"></address>
            </div>
        </div>
    </div>
</div>
