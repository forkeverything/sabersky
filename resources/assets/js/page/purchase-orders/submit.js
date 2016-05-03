Vue.component('purchase-orders-submit', {
    el: function () {
        return '#purchase-orders-submit';
    },
    data: function () {
        return {
            step: 1,
            ajaxReady: true,
            ajaxObject: {},
            response: {},
            projects: [],
            projectID: '',
            purchaseRequests: [],
            sort: 'number',
            order: 'asc',
            urgent: '',
            searchTerm: '',
            lineItems: [],
            vendorID: '',
            vendor: {
                linked_company: {}
            },
            addressID: '',
            selectedAddress: '',
            currencyID: '',
            currencySymbol: '',
            billingContactPerson: '',
            billingPhone: '',
            billingAddress1: '',
            billingAddress2: '',
            billingCity: '',
            billingZip: '',
            billingCountryID: '',
            billingState: '',
            shippingAddressSameAsBilling: true,
            shippingContactPerson: '',
            shippingPhone: '',
            shippingAddress1: '',
            shippingAddress2: '',
            shippingCity: '',
            shippingZip: '',
            shippingCountryID: '',
            shippingState: ''
        };
    },
    props: ['user'],
    computed: {
        hasPurchaseRequests: function () {
            return !_.isEmpty(this.purchaseRequests);
        },
        allPurchaseRequestsChecked: function () {
            var purchaseRequestIDs = _.map(this.purchaseRequests, function (request) {
                return request.id
            });
            var lineItemIDs = _.map(this.lineItems, function (item) {
                return item.id
            });
            return _.intersection(lineItemIDs, purchaseRequestIDs).length === purchaseRequestIDs.length;
        },
        hasLineItems: function () {
            return this.lineItems.length > 0;
        },
        vendorAddresses: function () {
            // Only if we have a vendor
            if (!this.vendor) return;
            // Grab the addresses associated with Vendor model
            var vendorAddresses = this.vendor.addresses;
            // If we have addresses and a linked company - add the Company's address
            if (vendorAddresses && this.vendor.linked_company_id) vendorAddresses.push(this.vendor.linked_company.address);
            return vendorAddresses;
        }
    },
    methods: {
        fetchPurchaseRequests: function (page) {
            var self = this;
            page = page || 1;

            var url = '/api/purchase_requests?' +
                'state=open' +
                '&quantity=1+' +
                '&project_id=' + self.projectID +
                '&sort=' + self.sort +
                '&order=' + self.order +
                '&per_page=8' +
                '&search=' + self.searchTerm;

            if (page) url += '&page=' + page;

            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            self.ajaxObject = $.ajax({
                url: url,
                method: 'GET',
                success: function (response) {
                    // Update data
                    self.response = response;

                    self.purchaseRequests = _.omit(response.data, 'query_parameters');

                    // Pull flags from response (better than parsing url)
                    self.sort = response.data.query_parameters.sort;
                    self.order = response.data.query_parameters.order;
                    self.urgent = response.data.query_parameters.urgent;

                    self.ajaxReady = true;

                    // self.$nextTick(function() {
                    //     self.finishLoading = true;
                    // })
                    // TODO ::: Add a loader for each request

                },
                error: function (res, status, req) {
                    console.log(status);
                    self.ajaxReady = true;
                }
            });
        },
        changeSort: function (sort) {
            if (this.sort === sort) {
                this.order = (this.order === 'asc') ? 'desc' : 'asc';
                this.fetchPurchaseRequests();
            } else {
                this.sort = sort;
                this.order = 'asc';
                this.fetchPurchaseRequests();
            }
        },
        searchPurchaseRequests: _.debounce(function () {
            var self = this;
            // If we're still waiting on a response cancel, abort, and fire a new request
            if (self.ajaxObject && self.ajaxObject.readyState != 4) self.ajaxObject.abort();
            self.fetchPurchaseRequests();
        }, 200),
        clearSearch: function () {
            this.searchTerm = '';
            this.searchPurchaseRequests();
        },
        selectPR: function (purchaseRequest) {
            this.alreadySelectedPR(purchaseRequest) ? this.lineItems = _.reject(this.lineItems, purchaseRequest) : this.lineItems.push(purchaseRequest);
        },
        alreadySelectedPR: function (purchaseRequest) {
            return _.find(this.lineItems, function (pr) {
                return pr.id === purchaseRequest.id;
            });
        },
        selectAllPR: function () {
            var self = this;
            if (self.allPurchaseRequestsChecked) {
                _.forEach(self.purchaseRequests, function (request) {
                    self.lineItems = _.reject(self.lineItems, request);
                });
            } else {
                _.forEach(self.purchaseRequests, function (request) {
                    if (!self.alreadySelectedPR(request)) self.lineItems.push(request);
                });
            }
        },
        showSinglePR: function (purchaseRequest) {
            this.$broadcast('modal-show-single-pr', purchaseRequest);
        },
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
            this.selectedAddress = this.selectedAddress ? null : address;
        },
        visibleAddress: function (address) {
            console.log('ran');
            console.log(!this.selectedAddress);
            if (_.isEmpty(this.selectedAddress)) return true;
            return this.selectedAddress == address;
        },
        calculateTotal: function (lineItem) {
            if (!lineItem.order_quantity || !lineItem.order_price) return '-';
            var currencySymbol = this.currencySymbol || '$';
            return accounting.formatMoney(lineItem.order_quantity * lineItem.order_price, currencySymbol + ' ', this.user.company.settings.currency_decimal_points);
        },
        createOrder: function() {

        }
    },
    events: {
        'go-to-page': function (page) {
            this.fetchPurchaseRequests(page);
        },
        'changed-currency': function(countryID) {
            var self = this;
            $.get('/countries/' + countryID, function (data) {
                self.currencySymbol = data.currency_symbol;
            });
        }
    },
    ready: function () {
        this.$watch('projectID', function (val) {
            if (!val) return;
            this.fetchPurchaseRequests();
        });
        this.$watch('vendorID', function (val) {
            var self = this;
            $.ajax({
                url: '/api/vendors/' + val,
                method: 'GET',
                success: function (data) {
                    self.vendor = data;
                },
                error: function (response) {
                    console.log(response);
                }
            });
        });
    }
});