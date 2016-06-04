<span class="card-title">Vendor</span>
<div class="name-group form-">
    <h4>Name</h4>
    <div class="name">
        @{{ vendor.name }}
        <vendor-connection :vendor="vendor"></vendor-connection>
    </div>
</div>
<div class="address-selection">
    <h4 :class="{ 'required' : PORequiresAddress }">Address</h4>
    <modal-select-address :selected.sync="selectedVendorAddress" :addresses.sync="vendor.addresses"></modal-select-address>
</div>
<div class="bank-selection">
    <h4 :class="{ 'required' : PORequiresBankAccount }">Bank Account</h4>
    <modal-select-bank-account :selected.sync="selectedVendorBankAccount" :accounts.sync="vendor.bank_accounts"></modal-select-bank-account>
</div>