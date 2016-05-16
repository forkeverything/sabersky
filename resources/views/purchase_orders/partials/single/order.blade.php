<!-- PO Single - Order -->

<div class="purchaser-addresses">
    <div class="meta hidden-xs">
        @include('purchase_orders.partials.single.meta')
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="billing-address">
        <h5>
            Billing Address
                <small v-if="purchaseOrder.billing_address_same_as_company">(company)</small>
        </h5>
                <address :address="purchaseOrder.billing_address" :company="company"></address>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="shipping-address">
                <h5>Shipping Address</h5>
                    <em v-if="purchaseOrder.shipping_address_same_as_billing" class="display-block">Same as billing address</em>
                    <address v-else :address="purchaseOrder.shipping_address" :company="company"></address>
            </div>
        </div>
    </div>
</div>
