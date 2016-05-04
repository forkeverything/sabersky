<h3>Vendor</h3>
<div class="name-group form-">
    <h5>Name</h5>
    <div class="name">
        @{{ vendor.name }}
        <vendor-connection :vendor="vendor"></vendor-connection>
    </div>
</div>
<div class="address-selection">
    <h5 :class="{ 'required' : PORequiresAddress }">Address</h5>
    <modal-select-address :selected.sync="selectedAddress" :addresses.sync="vendorAddresses"></modal-select-address>
</div>
<div class="bank-selection">
    <h5 :class="{ 'required' : PORequiresBankAccount }">Bank Account</h5>
    <modal-select-bank-account :selected.sync="selectedBankAccount" :accounts.sync="vendor.bank_accounts"></modal-select-bank-account>
</div>