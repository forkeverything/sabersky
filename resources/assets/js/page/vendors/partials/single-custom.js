Vue.component('vendor-custom', {
    name: 'vendorCustom',
    el: function () {
        return '#vendor-single-custom'
    },
    data: function () {
        return {
            ajaxReady: true,
            vendorID: '',
            vendor: {},
            description: '',
            editDescription: false,
            savedDescription: '',
            showAddBankAccountForm: false,
            accountName: '',
            accountNumber: '',
            bankName: '',
            swift: '',
            bankPhone: '',
            bankAddress: ''
        };
    },
    props: [],
    computed: {},
    methods: {
        startEditDescription: function () {
            this.editDescription = true;
            this.$nextTick(function () {
                $('.description-editor').focus();
            });
        },
        saveDescription: function () {
            this.editDescription = false;
            this.savedDescription = 'saving';
            var self = this;
            if (!self.ajaxReady) {
                self.savedDescription = 'error';
                return;
            }
            self.ajaxReady = false;
            $.ajax({
                url: '/vendors/' + self.vendorID + '/description',
                method: 'POST',
                data: {
                    "description": self.description
                },
                success: function (data) {
                    // success
                    self.savedDescription = 'success';
                    self.vendor.description = self.description;
                    self.ajaxReady = true;
                },
                error: function (response) {
                    self.savedDescription = 'error';
                    self.ajaxReady = true;
                }
            });
        },
        setPrimary: function (address) {
            var self = this;
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/api/address/' + address.id + '/set_primary',
                method: 'PUT',
                success: function (data) {
                    // success
                    self.vendor.addresses = _.map(self.vendor.addresses, function (vendorAddress) {
                        if (vendorAddress.id === address.id) {
                            vendorAddress.primary = 1;
                        } else {
                            vendorAddress.primary = 0;
                        }
                        return vendorAddress;
                    });
                    self.ajaxReady = true;
                },
                error: function (response) {
                    console.log(response);
                    self.ajaxReady = true;
                }
            });
        },
        removeAddress: function (address) {
            var self = this;
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/api/address/' + address.id,
                method: 'DELETE',
                success: function (data) {
                    // success
                    flashNotify('success', 'Removed address');
                    self.vendor.addresses = _.reject(self.vendor.addresses, address);
                    self.ajaxReady = true;
                },
                error: function (response) {
                    console.log(response);
                    self.ajaxReady = true;
                }
            });
        },
        toggleAddBankAccountForm: function () {
            this.showAddBankAccountForm = !this.showAddBankAccountForm;
        },
        addBankAccount: function () {
            var self = this;
            vueClearValidationErrors(self);
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/vendors/' + self.vendorID + '/bank_accounts',
                method: 'POST',
                data: {
                    "account_name": self.accountName,
                    "account_number": self.accountNumber,
                    "bank_name": self.bankName,
                    "swift": self.swift,
                    "bank_phone": self.bankPhone,
                    "bank_address": self.bankAddress
                },
                success: function (data) {
                    // Push to front
                    self.vendor.bank_accounts.push(data);
                    // Reset Fields
                    self.accountName = '';
                    self.accountNumber = '';
                    self.bankName = '';
                    self.swift = '';
                    self.bankPhone = '';
                    self.bankAddres = '';
                    // Flash
                    flashNotify('success', 'Added bank account to vendor')
                    self.ajaxReady = true;
                },
                error: function (response) {
                    flashNotify('error', 'Could not add bank account to vendor')
                    vueValidation(response, self);
                    self.ajaxReady = true;
                }
            });
        }
    },
    events: {
        'address-added': function (address) {
            this.vendor.addresses.push(address);
        }
    },
    ready: function () {
        var self = this;
        $.ajax({
            url: '/api/vendors/' + self.vendorID,
            method: 'GET',
            success: function (data) {
                self.vendor = data;
            },
            error: function (response) {
                console.log(response);
            }
        });
    }
});