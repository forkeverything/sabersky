Vue.component('purchase-orders-all', apiRequestAllBaseComponent.extend({
    name: 'allPurchaseOrders',
    el: function () {
        return '#purchase-orders-all';
    },
    data: function () {
        return {
            requestUrl: '/api/purchase_orders',
            statuses: ['pending', 'approved', 'rejected', 'all'],
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
                    value: 'total',
                    label: 'Total Cost'
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
                    value: 'submitted',
                    label: 'Submitted Date'
                },
                {
                    value: 'user_id',
                    label: 'Made by'
                }
            ]
        };
    },
    computed: {
        purchaseOrders: function () {
            return _.omit(this.response.data, 'query_parameters');
        }
    },
    methods: {
        changeStatus: function (status) {
            this.makeRequest(updateQueryString({
                status: status,
                page: 1
            }));
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
    }
}));