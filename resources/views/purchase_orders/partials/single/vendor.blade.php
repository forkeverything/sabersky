<h3>Vendor</h3>
<h2 class="name">@{{ purchaseOrder.vendor.name }}</h2>
<h5>Address</h5>
<address :address="purchaseOrder.vendor_address"></address>
<h5>Bank Account</h5>
<bank-account :bank-account="purchaseOrder.vendor_bank_account"></bank-account>