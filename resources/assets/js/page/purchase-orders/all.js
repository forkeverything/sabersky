Vue.component('purchase-orders-all',{
    name: 'allPurchaseOrders',
    el: function() {
        return '#purchase-orders-all';
    },
    data: function() {
        return {
            purchaseOrders: [],
            headings: [
                ['created_at', 'Date Submitted'],
                ['project.name', 'Project'],
                ['', 'Item(s)'],
                ['total', 'OrderTotal'],
                ['', 'Status'],
                ['', 'Paid'],
                ['', 'Delivered']
            ],
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
    ready: function () {
        var self = this;
        $.ajax({
            url: '/api/purchase_orders',
            method: 'GET',
            success: function (data) {
                self.purchaseOrders = data;
            },
            error: function (data) {
                console.log(data);
            }
        });
    },
    methods: {
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
    }
});