Vue.component('purchase-order-single', {
    name: 'purchaseOrderSingle',
    el: function() {
        return '#purchase-order-single'
    },
    data: function() {
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
    computed: {
        
    },
    methods: {
        formatRuleLimit: function(rule) {
            var currencySymbol = rule.trigger.has_currency ? rule.currency.symbol : null;
            return this.formatNumber(rule.limit, this.currencyDecimalPoints, currencySymbol);
        },
        changeTable: function(view) {
            this.tableView = view;
        }
    },
    events: {

    },
    mixins: [userCompany, numberFormatter],
    ready: function() {
        $.get('/api/purchase_orders/' + this.purchaseOrderID, function (data) {
            this.purchaseOrder = data;
        }.bind(this));
    }
}); 