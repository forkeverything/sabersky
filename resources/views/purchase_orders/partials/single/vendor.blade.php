<span class="subheading">Vendor</span>
<h2 class="name">{{ $purchaseOrder->vendor->name }}</h2>
<span class="subheading">Address</span>
    @include('layouts.partials.address', ['address' => $purchaseOrder->vendorAddress])
<span class="subheading">Bank Account</span>
@include('layouts.partials.bank_account', ['bankAccount' => $purchaseOrder->vendorBankAccount])