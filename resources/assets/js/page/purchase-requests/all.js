Vue.component('purchase-requests-all', apiRequestAllBaseComponent.extend({
    name: 'allPurchaseRequests',
    el: function () {
        return '#purchase-requests-all';
    },
    data: function () {
        return {
            requestUrl: '/api/purchase_requests',
            finishLoading: false,
            hasFilters: true,
            filterOptions: [
                {
                    value: 'number',
                    label: '# Number'
                },
                {
                    value: 'project_id',
                    label: 'Project'
                },
                {
                    value: 'quantity',
                    label: 'Quantity'
                },
                {
                    value: 'item_sku',
                    label: 'Item - SKU'
                },
                {
                    value: 'item_brand',
                    label: 'Item - Brand'
                },
                {
                    value: 'item_name',
                    label: 'Item - Name'
                },
                {
                    value: 'due',
                    label: 'Due Date'
                },
                {
                    value: 'requested',
                    label: 'Requested Date'
                },
                {
                    value: 'user_id',
                    label: 'Requester'
                }
            ],
            states: ['open', 'fulfilled', 'cancelled', 'all'],
            selectedRequests: []
        };
    },
    computed: {
        purchaseRequests: function() {
            return _.omit(this.response.data, 'query_parameters');
        },
        allPurchaseRequestsChecked: function () {
            var purchaseRequestIDs = _.map(_.filter(this.purchaseRequests, function(request) {
                return request.state === 'open';
            }), function (request) {
                return request.id;
            });

            var selectedRequestIDs = _.map(this.selectedRequests, function (request) {
                return request.id
            });
            return _.intersection(selectedRequestIDs, purchaseRequestIDs).length === purchaseRequestIDs.length;
        }
    },
    methods: {
        changeState: function (state) {
            this.makeRequest(updateQueryString({
                state: state,
                page: 1
            }));
        },
        toggleUrgentOnly: function () {
            var urgent = this.params.urgent ? 0 : 1;
            this.makeRequest(updateQueryString({
                state: this.params.state, // use same state
                page: 1, // Reset to page 1
                urgent: urgent
            }));
        },
        selectPR: function (purchaseRequest) {
            this.alreadySelectedPR(purchaseRequest) ? this.selectedRequests = _.reject(this.selectedRequests, purchaseRequest) : this.selectedRequests.push(purchaseRequest);
        },
        alreadySelectedPR: function (purchaseRequest) {
            return _.find(this.selectedRequests, function (pr) {
                return pr.id === purchaseRequest.id;
            });
        },
        selectAll: function() {
            var self = this;
            if (self.allPurchaseRequestsChecked) {
                _.forEach(self.purchaseRequests, function (request) {
                    self.selectedRequests = _.reject(self.selectedRequests, request);
                });
            } else {
                _.forEach(self.purchaseRequests, function (request) {
                    if (!self.alreadySelectedPR(request) && request.state === 'open') self.selectedRequests.push(request);
                });
            }
        }
    },
    ready: function () {
    }
}));