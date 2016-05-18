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
                rules: []
            },
            tableView: 'requests'
        };
    },
    props: [],
    computed: {},
    methods: {
        changeTable: function (view) {
            this.tableView = view;
        },
        markPaid: function(lineItem) {
            $.get('/purchase_orders/' + this.purchaseOrderID + '/line_item/' + lineItem.id + '/paid', function(data) {
                lineItem.paid = data;
            });
        },
        markReceived: function(lineItem, status) {
            if(status !== 'accepted' && status !== 'returned') return;
            $.get('/purchase_orders/' + this.purchaseOrderID + '/line_item/' + lineItem.id + '/received/' + status, function(data) {
                lineItem.status = data;
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