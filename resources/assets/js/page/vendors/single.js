Vue.component('vendor-single', {
    name: 'vendorSingle',
    el: function () {
        return '#vendor-single'
    },
    data: function () {
        return {
            ajaxReady: true,
            vendorID: '',
            vendor: {},
            description: '',
            editDescription: false,
            savedDescription: '',
            companyIDToLink: ''
        };
    },
    props: [],
    computed: {
        vendorLink: function () {
            if (this.vendor.linked_company_id) {
                if (this.vendor.verified) return 'verified';
                return 'pending';
            }
            return 'custom';
        }
    },
    methods: {
        startEditDescription: function () {
            this.editDescription = true;
            this.$nextTick(function () {
                $editor = $('.description-editor');
                $editor.focus();
                autosize.update($editor);
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
        addressSetPrimary: function (address) {
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
        bankSetPrimary: function (account) {
            var self = this;
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/vendors/' + self.vendorID + '/bank_accounts/' + account.id + '/set_primary',
                method: 'POST',
                success: function (data) {
                    self.vendor.bank_accounts = _.map(self.vendor.bank_accounts, function (bankAccount) {
                        if (bankAccount.id === account.id) {
                            bankAccount.primary = 1;
                        } else {
                            bankAccount.primary = 0;
                        }
                        return bankAccount;
                    });
                    self.ajaxReady = true;
                },
                error: function (response) {
                    flashNotify('error', 'Could not set Bank Account as primary');
                    self.ajaxReady = true;
                }
            });
        },
        deleteAccount: function (account) {
            var self = this;
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/vendors/' + self.vendor.id + '/bank_accounts/' + account.id,
                method: 'DELETE',
                success: function (data) {
                    self.vendor.bank_accounts = _.reject(self.vendor.bank_accounts, account);
                    flashNotify('success', 'Removed bank account');
                    self.ajaxReady = true;
                },
                error: function (response) {
                    console.log(response);
                    flashNotify('error', 'Could not remove bank account');
                    self.ajaxReady = true;
                }
            });
        },
        unlinkCompany: function () {
            var self = this;
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/vendors/' + self.vendor.id + '/unlink',
                method: 'PUT',
                data: {
                    "vendor_id": self.vendor.id
                },
                success: function (data) {
                    // success
                    flashNotify('info', 'Unlinked company to vendor');
                    self.vendor = data;
                    self.ajaxReady = true;
                },
                error: function (response) {
                    console.log(response);

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