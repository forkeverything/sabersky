Vue.component('purchase-requests-all', {
    name: 'allPurchaseRequests',
    el: function () {
        return '#purchase-requests-all';
    },
    data: function () {
        return {
            response: {},
            params: {},
            showFiltersDropdown: false,

            filter: '',
            filterValue: '',
            minFilterValue: ' ',
            maxFilterValue: ' ',

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
            states: ['open', 'fulfilled', 'cancelled', 'all'],
            ajaxReady: true,
            finishLoading: false
        };
    },
    computed: {
        purchaseRequests: function() {
            return _.omit(this.response.data, 'query_parameters');
        }
    },
    methods: {
        fetchPurchaseRequests: function (query) {

            var self = this,
                url = '/api/purchase_requests';

            // If we got a new query parameter, use it in our request - otherwise, try get query form address bar
            query = query || window.location.href.split('?')[1];
            // If we had a query (arg or parsed) - attach it to our url
            if(query) url = url + '?' + query;

            // self.finishLoading = false;

            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: url,
                method: 'GET',
                success: function (response) {
                    // Update data
                    self.response = response;

                    // Attach filters
                        // Reset obj
                        self.params = {};
                        // Loop through and attach everything (Only pre-defined keys in data obj above will be accessible with Vue)
                        _.forEach(response.data.query_parameters, function (value, key) {
                            self.params[key] = value;
                        });


                    // push state (if query is different from url)
                    pushStateIfDiffQuery(query);

                    document.getElementById('body-content').scrollTop = 0;
                    
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
        changeState: function (stateName) {
            this.fetchPurchaseRequests(updateQueryString({
                state: stateName,
                page: 1
            }));
        },
        toggleUrgentOnly: function () {
            var urgent = this.params.urgent ? 0 : 1;
            this.fetchPurchaseRequests(updateQueryString({
                state: this.params.state, // use same state
                page: 1, // Reset to page 1
                urgent: urgent
            }));
        },
        changeSort: function (sort) {
            if (this.params.sort === sort) {
                var newOrder = (this.params.order === 'asc') ? 'desc' : 'asc';
                this.fetchPurchaseRequests(updateQueryString('order', newOrder));
            } else {
                this.fetchPurchaseRequests(updateQueryString({
                    sort: sort,
                    order: 'asc',
                    page: 1
                }));
            }
        },
        removeFilter: function (type) {
            var queryObj = {
                page: 1
            };
            queryObj[type] = null;
            this.fetchPurchaseRequests(updateQueryString(queryObj))
        },
        addPRsFilter: function() {
            var self = this;
            var value = self.filterValue || [self.minFilterValue, self.maxFilterValue];

            self.fetchPurchaseRequests(updateQueryString(self.filter, value));

            // Reset values
            this.filter = '';
            this.filterValue = '';
            this.minFilterValue = ' ';
            this.maxFilterValue = ' ';

            // Hide dropdown
            this.showFiltersDropdown = false;
        },
        removeAllFilters: function() {
            var self = this;
            var queryObj = {};
            _.forEach(self.filterOptions, function (option) {
                queryObj[option.value] = null;
            });
            this.fetchPurchaseRequests(updateQueryString(queryObj));
        }
    },
    ready: function () {
        // If exists
        this.fetchPurchaseRequests();
        onPopCallFunction(this.fetchPurchaseRequests);
    }
});