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
            finishLoading: false,
            itemsPerPage: 8,
            itemsPerPageOptions: [
                {
                    value: 8,
                    label: '8 Requests / Page'
                }, {
                    value: 16,
                    label: '16 Requests / Page'
                },
                {
                    value: 32,
                    label: '32 Requests / Page'
                }
            ]
        };
    },
    computed: {
        paginatedPages: function () {
            switch (this.currentPage) {
                case 1:
                case 2:
                    if(this.lastPage > 0) {
                        var endPage = (this.lastPage < 5) ? this.lastPage : 5;
                        return this.makePagesArray(1, endPage);
                    } else {
                        return this.makePagesArray(1, 5);
                    }
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
        setLoadQuery: function () {
            var currentQuery = window.location.href.split('?')[1];
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

                    // set flags
                    self.filter = response.data.filter;
                    self.sort = response.data.sort;
                    self.order = response.data.order;
                    self.urgent = response.data.urgent;
                    self.lastPage = response.last_page;
                    self.currentPage = response.current_page;
                    self.itemsPerPage = response.per_page;

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
        goToPage: function (page) {
            if (0 < page && page <= this.lastPage) this.fetchPurchaseRequests(updateQueryString('page', page));
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
        },
        changeItemsPerPage: function() {
            this.fetchPurchaseRequests(updateQueryString({
                filter: this.filter, // use same filter
                page: 1, // Reset to page 1
                urgent: (this.urgent) ? 1 : 0, // Keep urgent flag
                per_page: this.itemsPerPage
            }));
        }
    },
    ready: function () {
        // If exists
        this.fetchPurchaseRequests(this.setLoadQuery());

        // window.onpopstate = function (e) {
        //     if (e.state) {
        //         this.fetchPurchaseRequests(window.location.href.split('?')[1]);
        //     }
        // }.bind(this);

        onPopQuery(this.fetchPurchaseRequests);
    }
});