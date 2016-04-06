Vue.component('purchase-requests-all', {
    name: 'allPurchaseRequests',
    el: function () {
        return '#purchase-requests-all';
    },
    data: function () {
        return {
            response: {},
            headings: [
                ['due', 'Due Date'],
                ['project.name', 'Project'],
                ['item.name', 'Item'],
                ['specification', 'Specification'],
                ['quantity', 'Quantity'],
                ['user.name', 'Made by'],
                ['created_at', 'Requested']
            ],
            order: '',
            urgent: '',
            filter: '',
            sort:'',
            lastPage: '',
            currentPage: '',
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
            showFilterDropdown: false,
        };
    },
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
        toggleUrgent: function () {
            this.urgent = (this.urgent) ? '' : 1;
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
        fetchPurchaseRequests: function(query) {
            var url = query ? '/api/purchase_requests?' + query : '/api/purchase_requests';
            var self = this;
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
                    if(query !== window.location.href.split('?')[1]) {
                        window.history.pushState({},"", '?' + query);
                    }
                },
                error: function (res, status, req) {
                    console.log(status);
                }
            });
        },
        updateQuery: function(name, value) {
            var fullQuery = window.location.href.split('?')[1];
            var queryArray = fullQuery ? fullQuery.split('&') : [];
            var queryObj = {};

            // Build up object
            queryArray.forEach(function (item) {
                var x = item.split('=');
                queryObj[x[0]] = x[1];
            });

            queryObj[name] = value; // Set the new name and value

            var newQuery = '';

            _.forEach(queryObj, function (value, name) {
                newQuery += name + '=' + value + '&';
            });

            return newQuery.substring(0, newQuery.length - 1);  // Trim last '&'
        },
        goToPage: function(page) {
            this.fetchPurchaseRequests(this.updateQuery('page', page));
        },
        changeFilter: function(filter) {
            this.filter = filter;
            this.showFilterDropdown = false;
            this.fetchPurchaseRequests(this.updateQuery('filter', filter.name));
        },
        setLoadQuery: function() {
            var currentQuery = window.location.href.split('?')[1];
            currentQuery = getParameterByName('filter') ? currentQuery : this.updateQuery('filter', 'open');
            return currentQuery;
        }
    },
    ready: function () {
            // If exists
        this.fetchPurchaseRequests(this.setLoadQuery());

        window.onpopstate = function(e){
            if(e.state){
                this.fetchPurchaseRequests(window.location.href.split('?')[1]);
            }
        }.bind(this);


        this.updateQuery('rina', 'boo');


    }
});