<h3>Order</h3>

<div class="billing-address">
    <h5>
        Billing Address @if($purchaseOrder->billingAddressSameAsCompany())<small>(company)</small>@endif
    </h5>

    @include('layouts.partials.address', ['company' => Auth::user()->company, 'address' => $purchaseOrder->billingAddress])
</div>

<div class="shipping-address">
    <h5>Shipping Address</h5>
    @if($purchaseOrder->shippingAddressSameAsBilling())
        <em class="display-block">Same as billing address</em>
    @else
        @include('layouts.partials.address', ['company' => Auth::user()->company, 'address' => $purchaseOrder->shippingAddress])
    @endif
</div>