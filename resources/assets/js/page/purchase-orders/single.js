Vue.component('purchase-order-single', {
    name: 'purchaseOrderSingle',
    el: function () {
        return '#purchase-order-single'
    },
    data: function () {
        return {
            tableView: 'requests'
        };
    },
    props: ['purchase-order', 'xhr'],
    computed: {
        numItems: function () {
            return this.purchaseOrder.items.length;
        },
        numLineItems: function () {
            return this.purchaseOrder.line_items.length;
        },
        numPaidLineItems: function () {
            return _.filter(this.purchaseOrder.line_items, function (lineItem) {
                return lineItem.paid;
            }).length;
        },
        numReceivedLineItems: function () {
            return _.filter(this.purchaseOrder.line_items, function (lineItem) {
                return lineItem.received;
            }).length;
        },
        numAcceptedLineItems: function () {
            return _.filter(this.purchaseOrder.line_items, function (lineItem) {
                return lineItem.accepted;
            }).length;
        },
        numReturnedLineItems: function () {
            return _.filter(this.purchaseOrder.line_items, function (lineItem) {
                return lineItem.returned;
            }).length;
        }
    },
    methods: {
        changeTable: function (view) {
            this.tableView = view;
        },
        markPaid: function (lineItem) {
            $.get('/purchase_orders/' + this.purchaseOrder.id + '/line_item/' + lineItem.id + '/paid', function (data) {
                lineItem.paid = data;
            });
        },
        markAllPaid: function () {
            var self = this;
            _.forEach(self.purchaseOrder.line_items, function (lineItem) {
                self.markPaid(lineItem);
            });
        }
    },
    events: {},
    mixins: [userCompany, numberFormatter],
    ready: function () {

        var self = this;
        pusherChannel.bind('App\\Events\\PurchaseOrderUpdated', function (data) {
            // status
            self.purchaseOrder.status = data.purchaseOrder.status;
        });

        pusherChannel.bind('App\\Events\\RuleProcessed', function (data) {
            var rule = _.find(self.purchaseOrder.rules, {'id': data.rule.id});
            rule.pivot.approved = data.approval;
        });

        pusherChannel.bind('App\\Events\\LineItemUpdated', function (data) {
            // fetch our LineItem
            var lineItem = _.find(self.purchaseOrder.line_items, {'id': data.lineItem.id});
            // update status
            lineItem.status = data.lineItem.status;
            // update paid
            lineItem.paid = data.lineItem.paid;
        });

    }
});