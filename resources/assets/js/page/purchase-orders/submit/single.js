Vue.component('purchase-order-single', {
    name: 'purchaseOrderSingle',
    el: function() {
        return '#purchase-order-single'
    },
    data: function() {
        return {
            purchaseOrder: {
                vendor: {},
                user: {},
                rules: []
            }
        };
    },
    props: [],
    computed: {
        
    },
    methods: {
        formatRuleLimit: function(rule) {
            var currencySymbol = rule.trigger.has_currency ? this.userCurrency.symbol : null;
            return this.formatNumber(rule.limit, this.currencyDecimalPoints, currencySymbol);
        }
    },
    events: {

    },
    mixins: [userCompany, numberFormatter],
    ready: function() {
        var url = window.location.href;
        var purchaseOrderID = url.split('purchase_orders/')[1];
        $.get('/api/purchase_orders/' + purchaseOrderID, function (data) {
            this.purchaseOrder = data;
        }.bind(this));
    }
}); 