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
            lastPage: '',
            currentPage: '',
            showFilterDropdown: false,
            filters: [
                {
                    name: 'open',   // What gets sent to server
                    label: 'Open'   // Displayed to client
                },
                {
                    name: 'complete',
                    label: 'Fulfilled'
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
    computed: {
        paginatedPages: function () {
            switch (this.currentPage) {
                case 1:
                case 2:
                    var endPage = (this.lastPage < 5) ? this.lastPage : 5;
                    return this.makePagesArray(1, endPage);
                    break;
                case this.lastPage:
                case this.lastPage - 1:
                    var startPage = (this.lastPage > 5) ? this.lastPage - 4 : 1;
                    var endPage = this.lastPage;
                    return this.makePagesArray(startPage, endPage);
                    break;
                default:
                    var startPage = this.currentPage - 2;
                    var endPage = this.currentPage + 2;
                    return this.makePagesArray(startPage, endPage);
            }
        }
    },
    methods: {
        makePagesArray: function (startPage, endPage) {
            var pagesArray = [];
            for (var i = startPage; i <= endPage; i++) {
                pagesArray.push(i);
            }
            return pagesArray;
        },
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
                    console.log(purchaseRequest.state);
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
        fetchPurchaseRequests: function (query) {
            var url = query ? '/api/purchase_requests?' + query : '/api/purchase_requests';
            var self = this;

            // self.finishLoading = false;

            if(!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: url,
                method: 'GET',
                success: function (response) {
                    // Update data
                    self.response = response;

                    // set flags
                    self.filter = response.data.filter;
                    self.sort = response.data.sort;
                    self.order = response.data.order;
                    self.urgent = response.data.urgent;
                    self.lastPage = response.last_page;
                    self.currentPage = response.current_page;

                    // push state (if query is different from url)
                    if (query !== window.location.href.split('?')[1]) {
                        window.history.pushState({}, "", '?' + query);
                    }
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
        updateQuery: function (name, value) {
            var fullQuery = window.location.href.split('?')[1];
            var queryArray = fullQuery ? fullQuery.split('&') : [];
            var queryObj = {};

            // Build up object
            queryArray.forEach(function (item) {
                var x = item.split('=');
                queryObj[x[0]] = x[1];
            });

            /**
             * TO DO CHECK HERE
             */
            if (typeof arguments[0] === 'string') {
                queryObj[arguments[0]] = arguments[1]; // Set the new name and value
            } else {
                // Received an object with key-value pairs of query names
                _.forEach(arguments[0], function (value, key) {
                    queryObj[key] = value;
                });
            }


            // _.forEach()

            var newQuery = '';

            _.forEach(queryObj, function (value, name) {
                newQuery += name + '=' + value + '&';
            });

            return newQuery.substring(0, newQuery.length - 1);  // Trim last '&'
        },
        goToPage: function (page) {
            if (0 < page && page <= this.lastPage) this.fetchPurchaseRequests(this.updateQuery('page', page));
        },
        changeFilter: function (filter) {
            this.filter = filter;
            this.showFilterDropdown = false;
            this.fetchPurchaseRequests(this.updateQuery({
                filter: filter.name,
                page: 1
            }));
        },
        toggleUrgentOnly: function () {
            var urgent = this.urgent ? 0 : 1;
            this.fetchPurchaseRequests(this.updateQuery({
                filter: this.filter, // use same filter
                page: 1, // Reset to page 1
                urgent: urgent
            }));
        },
        setLoadQuery: function () {
            var currentQuery = window.location.href.split('?')[1];
            currentQuery = getParameterByName('filter') ? currentQuery : this.updateQuery('filter', 'open');
            return currentQuery;
        },
        changeSort: function(sort) {
            if(this.sort === sort) {
                var newOrder = (this.order === 'asc') ? 'desc' : 'asc';
                this.fetchPurchaseRequests(this.updateQuery('order', newOrder));
            } else {
                this.fetchPurchaseRequests(this.updateQuery({
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

        window.onpopstate = function (e) {
            if (e.state) {
                this.fetchPurchaseRequests(window.location.href.split('?')[1]);
            }
        }.bind(this);


        this.updateQuery('rina', 'boo');
    }
});