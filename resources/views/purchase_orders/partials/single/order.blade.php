<!-- PO Single - Order -->

<div class="meta hidden-xs">
    @include('purchase_orders.partials.single.meta')
</div>

<div class="purchaser-addresses">
    <div class="row">
        <div class="col-sm-6">
            <div class="billing-address">
        <span class="subheading">
            Billing Address @if($purchaseOrder->billingAddressSameAsCompany())
                <small>(company)</small>@endif
        </span>

                @include('layouts.partials.address', ['company' => Auth::user()->company, 'address' => $purchaseOrder->billingAddress])
            </div>
        </div>
        <div class="col-sm-6">
            <div class="shipping-address">
                <span class="subheading">Shipping Address</span>
                @if($purchaseOrder->shippingAddressSameAsBilling())
                    <em class="display-block">Same as billing address</em>
                @else
                    @include('layouts.partials.address', ['company' => Auth::user()->company, 'address' => $purchaseOrder->shippingAddress])
                @endif
            </div>
        </div>
    </div>
</div>
