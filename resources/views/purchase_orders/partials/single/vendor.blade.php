<h1 class="name">@{{ purchaseOrder.vendor.name }}</h1>
<h4>Address</h4>

<address :address="purchaseOrder.vendor_address"></address>
<h4>Bank Account</h4>
<bank-account :account="purchaseOrder.vendor_bank_account"></bank-account>