<div class="add-form-wrap" :class="{ 'expanded': showAddBankAccountForm }">
    <form-errors></form-errors>
    <form @submit.prevent="addBankAccount" id="form_add_bank_account">
        <h4>Add New Bank Account</h4>
        <div class="account_info">
            <label>Account Information</label>
            <div class="row">
                <div class="col-xs-6">
                    <div class="shift-label-input no-validate">
                        <input type="text" v-model="accountName" required>
                        <label placeholder="Account Name" class="required"></label>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="shift-label-input no-validate">
                        <input type="text" v-model="accountNumber" required>
                        <label placeholder="# Number" class="required"></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="bank_info">
            <label>Bank Details</label>
            <div class="visible-xs">
                @include('vendors.partials.single.form_add_bank.input_bank_name')
            </div>
            <div class="row hidden-xs">
                <div class="col-sm-4">
                    @include('vendors.partials.single.form_add_bank.input_bank_name')
                </div>
                <div class="col-sm-4">
                    @include('vendors.partials.single.form_add_bank.input_swift')
                </div>
                <div class="col-sm-4">
                    @include('vendors.partials.single.form_add_bank.input_phone')
                </div>
            </div>
            <div class="row visible-xs">
                <div class="col-xs-6">
                    @include('vendors.partials.single.form_add_bank.input_swift')
                </div>
                <div class="col-xs-6">
                    @include('vendors.partials.single.form_add_bank.input_phone')
                </div>
            </div>
            <div class="shift-label-input no-validate">
                <input type="text" class="not-required" v-model="bankAddress" :class="{ 'filled': bankAddress.length > 0 }">
                <label placeholder="Address"></label>
            </div>
        </div>
        <div class="align-end">
            <button type="submit" class="btn btn-solid-blue"><i class="fa fa-plus"></i> Bank Account</button>
        </div>
    </form>
</div>