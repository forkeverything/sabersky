Vue.component('purchase-requests-all', {
    name: 'allPurchaseRequests',
    el: function () {
        return '#purchase-requests-all';
    },
    data: function () {
        return {
            response: {},
            order: '',
            urgent: '',
            filter: '',
            sort: '',
            showFilterDropdown: false,
            filters: [
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
        loadSinglePR: function (id) {
            window.document.location = '/purchase_requests/single/' + id;
        },
        changeSort: function ($newField) {
            if (this.field == $newField) {
                this.order = (this.order == '') ? -1 : '';
            } else {
                this.field = $newField;
                this.order = ''
            }
        },
        checkShow: function (purchaseRequest) {
            switch (this.filter) {
                case 'complete':
                    if (purchaseRequest.state == 'Open' && purchaseRequest.quantity == '0') {
                        return true;
                    }
                    break;
                case 'cancelled':
                    if (purchaseRequest.state == 'Cancelled') {
                        return true;
                    }
                    break;
                default:
                    if (purchaseRequest.quantity > 0 && purchaseRequest.state !== 'Cancelled') {
                        return true;
                    }
            }
        },
        setLoadQuery: function () {
            // The currenty query
            var currentQuery = window.location.href.split('?')[1];
            // If filter set - use query. Else - set a default for the filter
            currentQuery = getParameterByName('filter') ? currentQuery : updateQueryString('filter', 'open');
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

                    // Pull flags from response (better than parsing url)
                    self.filter = response.data.filter;
                    self.sort = response.data.sort;
                    self.order = response.data.order;
                    self.urgent = response.data.urgent;

                    // push state (if query is different from url)
                    pushStateIfDiffQuery(query);
                    
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
        changeFilter: function (filter) {
            this.filter = filter;
            this.showFilterDropdown = false;
            this.fetchPurchaseRequests(updateQueryString({
                filter: filter.name,
                page: 1
            }));
        },
        toggleUrgentOnly: function () {
            var urgent = this.urgent ? 0 : 1;
            this.fetchPurchaseRequests(updateQueryString({
                filter: this.filter, // use same filter
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
        }
    },
    ready: function () {
        // If exists
        this.fetchPurchaseRequests(this.setLoadQuery());

        onPopQuery(this.fetchPurchaseRequests);
    }
});