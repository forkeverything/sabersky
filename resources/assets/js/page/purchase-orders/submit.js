Vue.component('purchase-orders-submit', {
    el: function () {
        return '#purchase-orders-submit';
    },
    data: function () {
        return {
            step: 1,
            ajaxReady: true,
            lineItems: [],
            vendor: {
                linked_company: {},
                addresses: [],
                bank_accounts: []
            },
            selectedVendorAddress: '',
            selectedVendorBankAccount: '',
            currency: '',
            billingAddressSameAsCompany: 1,
            billingAddress: {
                contact_person: '',
                phone: '',
                address_1: '',
                address_2: '',
                city: '',
                zip: '',
                country_id: '',
                state: ''
            },
            shippingAddressSameAsBilling: 1,
            shippingAddress: {
                contact_person: '',
                phone: '',
                address_1: '',
                address_2: '',
                city: '',
                zip: '',
                country_id: '',
                state: ''
            },
            additionalCosts: []
        };
    },
    props: ['user'],
    computed: {
        hasLineItems: function () {
            return this.lineItems.length > 0;
        },
        sortedLineItems: function () {
            return _.orderBy(this.lineItems, 'item.id');
        },
        vendorAddresses: function () {
            // Only if we have a vendor
            if (!this.vendor.id) return [];
            // Grab the addresses associated with Vendor model
            var vendorAddresses = this.vendor.addresses || [];
            // If we have addresses and a linked company - add the Company's address
            if (vendorAddresses && this.vendor.linked_company_id) vendorAddresses.push(this.vendor.linked_company.address);
            return vendorAddresses;
        },
        validBillingAddress: function () {
            return !!this.billingAddress.phone && !!this.billingAddress.address_1 && !!this.billingAddress.city && !!this.billingAddress.zip && !!this.billingAddress.country_id && !!this.billingAddress.state;
        },
        validShippingAddress: function () {
            return !!this.shippingAddress.phone && !!this.shippingAddress.address_1 && !!this.shippingAddress.city && !!this.shippingAddress.zip && !!this.shippingAddress.country_id && !!this.shippingAddress.state;
        },
        canCreateOrder: function () {
            var validVendor = true,
                validOrder = true,
                validItems = true;

            // Vendor
            // one selected
            if (!this.vendor.id) validVendor = false;
            // if we need address and no address
            if (this.PORequiresAddress && !this.selectedVendorAddress) validVendor = false;
            // if we need bank account and no bank account selected
            if (this.PORequiresBankAccount && !this.selectedVendorBankAccount) validVendor = false;

            // Order
            // currency set!
            if (!this.currency) validOrder = false;
            // Billing address required fields valid
            if (!this.billingAddressSameAsCompany && !this.validBillingAddress) validOrder = false;
            // If shipping NOT the same &&  Shipping address required fields not valid
            if (!this.shippingAddressSameAsBilling && !this.validShippingAddress) validOrder = false;

            // Items
            // Make sure we have some items
            if (!this.lineItems.length > 0) validItems = false;
            // for each line item...
            _.forEach(this.lineItems, function (item) {
                // quantity and price is filled
                if (!item.order_quantity || item.order_quantity < 1 || !item.order_price) validItems = false;
                // quantity to order <= quantity requested
                if (item.order_quantity > item.quantity) validItems = false;
            });

            // Create away if all valid
            return validVendor && validOrder && validItems
        }
    },
    methods: {
        removeLineItem: function (lineItem) {
            this.lineItems = _.reject(this.lineItems, lineItem);
        },
        clearAllLineItems: function () {
            this.lineItems = [];
        },
        goStep: function (step) {
            this.step = step;
        },
        selectAddress: function (address) {
            this.selectedVendorAddress = this.selectedVendorAddress ? null : address;
        },
        visibleAddress: function (address) {
            if (_.isEmpty(this.selectedVendorAddress)) return true;
            return this.selectedVendorAddress == address;
        },
        calculateTotal: function (lineItem) {
            if (!lineItem.order_quantity || !lineItem.order_price) return '-';
            var currencySymbol = this.currencySymbol || '$';
            return accounting.formatMoney(lineItem.order_quantity * lineItem.order_price, currencySymbol + ' ', this.currencyDecimalPoints);
        },
        createOrder: function () {
            var self = this;
            vueClearValidationErrors(self);
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/api/purchase_orders/submit',
                method: 'POST',
                data: {
                    "vendor_id": self.vendor.id,
                    "vendor_address_id": self.selectedVendorAddress.id,
                    "vendor_bank_account_id": self.selectedVendorBankAccount.id,
                    "currency_id": self.currency.id,
                    "billing_address_same_as_company": self.billingAddressSameAsCompany,
                    "billing_contact_person": self.billingAddress.contact_person,
                    "billing_phone": self.billingAddress.phone,
                    "billing_address_1": self.billingAddress.address_1,
                    "billing_address_2": self.billingAddress.address_2,
                    "billing_city": self.billingAddress.city,
                    "billing_zip": self.billingAddress.zip,
                    "billing_country_id": self.billingAddress.country_id,
                    "billing_state": self.billingAddress.state,
                    "shipping_address_same_as_billing": self.shippingAddressSameAsBilling,
                    "shipping_contact_person": self.shippingAddress.contact_person,
                    "shipping_phone": self.shippingAddress.phone,
                    "shipping_address_1": self.shippingAddress.address_1,
                    "shipping_address_2": self.shippingAddress.address_2,
                    "shipping_city": self.shippingAddress.city,
                    "shipping_zip": self.shippingAddress.zip,
                    "shipping_country_id": self.shippingAddress.country_id,
                    "shipping_state": self.shippingAddress.state,
                    "line_items": self.lineItems,
                    "additional_costs": self.additionalCosts
                },
                success: function (data) {
                    // success
                    flashNotifyNextRequest('success', 'Submitted Purchase Order');
                    location.href = "/purchase_orders";
                    self.ajaxReady = true;
                },
                error: function (response) {
                    console.log(response);
                    vueValidation(response, self);
                    self.ajaxReady = true;
                }
            });
        },
        updateOtherLineItemPrices: function (changedLineItem) {
            var self = this;

            var otherLineItemsWithSameItem = _.filter(self.lineItems, function (lineItem) {
                return lineItem.item.id === changedLineItem.item.id;
            });

            _.forEach(otherLineItemsWithSameItem, function (lineItem) {
                if(lineItem.id === changedLineItem.id) return;
                var index = _.indexOf(self.lineItems, lineItem);
                Vue.set(self.lineItems[index], 'order_price', changedLineItem.order_price);
            });
        },
        firstLineItemWithItem: function(lineItem) {
            var firstLineItem = _.find(this.lineItems, function (l) {
                return l.item.id === lineItem.item.id;
            });
            return firstLineItem.id == lineItem.id;
        }
    },
    mixins: [userCompany, modalSinglePR],
    ready: function () {

        var self = this;

        vueEventBus.$on('po-submit-selected-vendor', function () {
            self.selectedVendorAddress = '';
            self.selectedVendorBankAccount = '';
        });

        var requestParam = getParameterByName('request');
        if (requestParam) {
            var preSelectedRequestIDs = getParameterByName('request').split(',');
            _.forEach(preSelectedRequestIDs, function (id) {
                $.get('/api/purchase_requests/' + id, function (request) {
                    if (request.state === 'open') self.lineItems.push(request);
                });
            });
        }

        vueEventBus.$on('update-line-item-price', function (data) {
            self.updateOtherLineItemPrices(data.attached);
        });

    }
});