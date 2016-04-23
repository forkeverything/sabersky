Vue.component('purchase-requests-all', {
    name: 'allPurchaseRequests',
    el: function () {
        return '#purchase-requests-all';
    },
    data: function () {
        return {
            response: {},
            purchaseRequests: [],
            order: '',
            urgent: '',
            state: '',
            filter: '',
            sort: '',
            showStatesDropdown: false,
            showFiltersDropdown: false,

            filterValue: '',
            minFilterValue: ' ',
            maxFilterValue: ' ',

            activeFilters: {
               number_filter_integer: '',
                project: '',
                quantity_filter_integer: '',
                item_brand: '',
                item_name: ''
            },

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
            states: [
                {
                    name: 'open',   // What gets sent to server
                    label: 'Open'   // Displayed to client
                },
                {
                    name: 'complete',
                    label: 'Completed'
                },
                {
                    name: 'cancelled',
                    label: 'Cancelled'
                },
                {
                    name: 'all',
                    label: 'All Statuses'
                }
            ],
            ajaxReady: true,
            finishLoading: false
        };
    },
    computed: {},
    methods: {
        setLoadQuery: function () {
            // The currenty query
            var currentQuery = window.location.href.split('?')[1];
            // If state set - use query. Else - set a default for the state
            currentQuery = getParameterByName('state') ? currentQuery : updateQueryString('state', 'open');
            return currentQuery;
        },
        fetchPurchaseRequests: function (query) {
            var url = query ? '/api/purchase_requests?' + query : '/api/purchase_requests';
            var self = this;

            // self.finishLoading = false;

            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: url,
                method: 'GET',
                success: function (response) {
                    // Update data
                    self.response = response;
                    self.purchaseRequests = _.omit(response.data, 'query_parameters');

                    // Pull flags from response (better than parsing url)
                    self.state = response.data.query_parameters.state;
                    self.sort = response.data.query_parameters.sort;
                    self.order = response.data.query_parameters.order;
                    self.urgent = response.data.query_parameters.urgent;

                    // Attach filters
                        // Reset obj
                        self.activeFilters = {};
                        // Loop through and attach everything (Only pre-defined keys in data obj above will be accessible with Vue)
                        _.forEach(response.data.query_parameters, function (value, key) {
                            self.activeFilters[key] = value;
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
        changeState: function (state) {
            this.state = state;
            this.showStatesDropdown = false;
            this.fetchPurchaseRequests(updateQueryString({
                state: state.name,
                page: 1
            }));
        },
        toggleUrgentOnly: function () {
            var urgent = this.urgent ? 0 : 1;
            this.fetchPurchaseRequests(updateQueryString({
                state: this.state, // use same state
                page: 1, // Reset to page 1
                urgent: urgent
            }));
        },
        changeSort: function (sort) {
            if (this.sort === sort) {
                var newOrder = (this.order === 'asc') ? 'desc' : 'asc';
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
        this.fetchPurchaseRequests(this.setLoadQuery());

        onPopQuery(this.fetchPurchaseRequests);
    }
});