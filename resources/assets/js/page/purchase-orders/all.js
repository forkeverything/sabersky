Vue.component('purchase-orders-all', {
    name: 'allPurchaseOrders',
    el: function () {
        return '#purchase-orders-all';
    },
    data: function () {
        return {
            ajaxReady: true,
            response: {},
            params: {},
            headings: [
                ['created_at', 'Date Submitted'],
                ['project.name', 'Project'],
                ['', 'Item(s)'],
                ['total', 'OrderTotal'],
                ['', 'Status'],
                ['', 'Paid'],
                ['', 'Delivered']
            ],
            activeStatus: 'pending',
            statuses: ['pending', 'approved', 'rejected', 'all']
        };
    },
    computed: {
        purchaseOrders: function () {
            return _.omit(this.response.data, 'query_parameters');
        }
    },
    methods: {
        fetchOrders: function (query) {
            var self = this,
                url = '/api/purchase_orders';

            query = query || window.location.href.split('?')[1];
            if (query) url = url + '?' + query;

            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: url,
                method: 'GET',
                success: function (response) {
                    // update response
                    self.response = response;
                    // update req. params
                    self.params = {};
                    _.forEach(response.data.query_parameters, function (value, key) {
                        self.params[key] = value;
                    });

                    pushStateIfDiffQuery(query);
                    document.getElementById('body-content').scrollTop = 0;

                    self.ajaxReady = true;

                    // TODO ::: Add a loader for each request
                },
                error: function (response) {
                    console.log(response);
                    self.ajaxReady = true;
                }
            });
        },
        changeStatus: function (status) {
            this.fetchOrders(updateQueryString({
                status: status,
                page: 1
            }));
        },
        changeSort: function (sort) {
            if (this.params.sort === sort) {
                var newOrder = (this.params.order === 'asc') ? 'desc' : 'asc';
                this.fetchOrders(updateQueryString('order', newOrder));
            } else {
                this.fetchOrders(updateQueryString({
                    sort: sort,
                    order: 'asc',
                    page: 1
                }));
            }
        },
        checkUrgent: function (purchaseOrder) {
            // takes a purchaseOrder and sees
            // if there are any PR's with urgent tags
            var urgent = false;
            _.forEach(purchaseOrder.line_items, function (item) {
                if (item.purchase_request.urgent) {
                    urgent = true;
                }
            });
            return urgent;
        },
        changeFilter: function (filter) {
            this.filter = filter;
        },
        toggleUrgent: function () {
            this.urgent = (this.urgent) ? '' : 1;
        },
        loadSinglePO: function (POID) {
            window.document.location = '/purchase_orders/single/' + POID;
        },
        checkProperty: function (purchaseOrder, property) {
            var numLineItems = purchaseOrder.line_items.length;
            var numTrueForProperty = 0;
            _.forEach(purchaseOrder.line_items, function (item) {
                item[property] ? numTrueForProperty++ : '';
            });
            if (numLineItems == numTrueForProperty) {
                return true;
            }
        }
    },
    ready: function () {
        this.fetchOrders();
        onPopCallFunction(this.fetchOrders);
    }
});