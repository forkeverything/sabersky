Vue.component('purchase-order-single', {
    name: 'purchaseOrderSingle',
    el: function () {
        return '#purchase-order-single'
    },
    data: function () {
        return {
            purchaseOrderID: '',
            purchaseOrder: {
                vendor: {},
                user: {},
                rules: [],
                line_items: [],
                items: []
            },
            tableView: 'requests'
        };
    },
    props: [],
    computed: {
        numItems: function() {
            return this.purchaseOrder.items.length;
        },
        numLineItems: function() {
            return this.purchaseOrder.line_items.length;
        },
        numPaidLineItems: function() {
            return _.filter(this.purchaseOrder.line_items, function(lineItem) {
                return lineItem.paid;
            }).length;
        },
        numReceivedLineItems: function() {
            return _.filter(this.purchaseOrder.line_items, function(lineItem) {
                return lineItem.received;
            }).length;
        },
        numAcceptedLineItems: function() {
            return _.filter(this.purchaseOrder.line_items, function(lineItem) {
                return lineItem.accepted;
            }).length;
        },
        numReturnedLineItems: function() {
            return _.filter(this.purchaseOrder.line_items, function(lineItem) {
                return lineItem.returned;
            }).length;
        }
    },
    methods: {
        changeTable: function (view) {
            this.tableView = view;
        },
        markPaid: function(lineItem) {
            $.get('/purchase_orders/' + this.purchaseOrderID + '/line_item/' + lineItem.id + '/paid', function(data) {
                lineItem.paid = data;
            });
        },
        markAllPaid: function() {
            var self = this;
            _.forEach(self.purchaseOrder.line_items, function (lineItem) {
                self.markPaid(lineItem);
            });
        }
    },
    events: {},
    mixins: [userCompany, numberFormatter],
    ready: function () {
        $.get('/api/purchase_orders/' + this.purchaseOrderID, function (data) {
            this.purchaseOrder = data;
        }.bind(this));
    }
});