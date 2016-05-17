Vue.component('po-single-rule', {
    name: 'purchaseOrderSingleRule',
    template: '<tr>' +
    '<td class="col-description">' +
    '{{ rule.property.label }} - {{ rule.trigger.label }} <span ' +
    'v-if="rule.trigger.has_limit">{{ formatRuleLimit(rule) }}</span>' +
    '</td>' +
    '<template v-if="rule.pivot.approved !== null">' +
    '<td v-if="rule.pivot.approved == 1" class="col-status">' +
    '<i class="fa fa-check icon-tick"></i>' +
    '</td>' +
    '<td v-if="rule.pivot.approved == 0" class="col-status">' +
    '<i class="fa fa-close icon-close"></i>' +
    '</td>' +
    '</template>' +
    '<template v-else>' +
    '<td class="col-approve fit-to-content col-buttons no-wrap" v-if="allowedUser">' +
    '<button type="button" class="btn btn-solid-green" @click="processRule(' + "'approve'" + ', rule)">' +
    '<i class="fa fa-check"></i>' +
    '</button>' +
    '<button type="button" class="btn btn-solid-red" @click="processRule(' + "'reject'" + ', rule)">' +
    '<i class="fa fa-close"></i>' +
    '</button>' +
    '</td>' +
    '<td v-else class="col-warning"><i class="fa fa-warning"></i></td>' +
    '</template>' +
    '</tr>',
    data: function () {
        return {};
    },
    props: ['purchase-order', 'rule', 'user'],
    computed: {
        allowedUser: function() {
            var self = this;
            return _.findIndex(this.rule.roles, function(role) { return role.id == self.user.role_id; }) !== -1;
        }
    },
    methods: {
        formatRuleLimit: function (rule) {
            var currencySymbol = rule.trigger.has_currency ? rule.currency.symbol : null;
            return this.formatNumber(rule.limit, this.currencyDecimalPoints, currencySymbol);
        },
        processRule: function (action, rule) {
            var self = this;
            if (action === 'approve') {
                $.get('/purchase_orders/' + self.purchaseOrder.id + '/rule/' + rule.id + '/approve', function (data) {
                    rule.pivot.approved = 1;
                    self.purchaseOrder.status = data;
                })
            } else {
                $.get('/purchase_orders/' + self.purchaseOrder.id + '/rule/' + rule.id + '/reject', function (data) {
                    rule.pivot.approved = 0;
                    self.purchaseOrder.status = data;
                })
            }
        }
    },
    mixins: [numberFormatter],
    events: {},
    ready: function () {

    }
});