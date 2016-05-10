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
            statuses: [
                {
                    key: 'pending',
                    label: 'Pending'
                },
                {
                    key: 'approved',
                    label: 'Approved'
                },
                {
                    key: 'rejected',
                    label: 'Rejected'
                },
                {
                    key: '',
                    label: 'All'
                }
            ],
            field: '',
            order: '',
            urgent: '',
            filter: 'pending'
        };
    },
    computed: {
        purchaseOrders: function () {
            return _.omit(this.response.data, 'query_parameters');
        }
    },
    methods: {
        setLoadQuery: function() {
            var currentQuery = window.location.href.split('?')[1];
            // If state set - use query. Else - set a default for the state
            currentQuery = getParameterByName('state') ? currentQuery : updateQueryString('state', 'open');
            return currentQuery;
        },
        fetchOrders: function (query) {
            var self = this,
                url = '/api/purchase_orders';

            if (query) url = url + '?' + query;

            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: url,
                method: 'GET',
                success: function (data) {
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
            this.activeStatus = status;
        },
        changeSort: function ($newField) {
            if (this.field == $newField) {
                this.order = (this.order == '') ? -1 : '';
            } else {
                this.field = $newField;
                this.order = ''
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