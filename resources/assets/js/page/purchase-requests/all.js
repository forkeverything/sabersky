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
            states: ['open', 'fulfilled', 'cancelled', 'all']
        };
    },
    computed: {
        purchaseRequests: function() {
            return _.omit(this.response.data, 'query_parameters');
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
        }
    },
    ready: function () {
    }
}));