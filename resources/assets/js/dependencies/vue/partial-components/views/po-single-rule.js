Vue.component('po-single-rule', {
    template: '<tr>' +
    '<td class="col-description">' +
    '{{ rule.property.label }} - {{ rule.trigger.label }} <span ' +
    'v-if="rule.trigger.has_limit">{{ formatRuleLimit(rule) }}</span>' +
    '</td>' +
    '<td class="col-approve">' +
    '<i v-if="approved" class="fa fa-check icon-check"></i>' +
    '<button type="button" class="btn btn-approve" v-if="! approved && allowedUser"  @click="processRule(' + "'approve'" + ', rule)"><i class="fa fa-check"></i></button>' +
    '<i v-if="! approved && ! allowedUser" class="icon-warning fa fa-warning"></i>' +
    '</td>' +
    '<td class="col-reject">' +
    '<i v-if="rejected" class="fa fa-close icon-close"></i>' +
    '<button type="button" class="btn btn-reject" v-if="!approved && !rejected && allowedUser"  @click="processRule(' + "'reject'" + ', rule)"><i class="fa fa-close"></i></button>' +
    '<i v-if="! rejected && ! allowedUser" class="icon-warning fa fa-warning"></i>' +
    '</td>' +
    '</tr>',
    name: 'purchaseOrderSingleRule',
    data: function () {
        return {};
    },
    props: ['purchase-order', 'rule'],
    computed: {
        approved: function() {
            return this.rule.pivot.approved;
        },
        rejected: function() {
            return this.rule.pivot.approved === 0;
        },
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
    mixins: [numberFormatter, userCompany],
    events: {},
    ready: function () {

    }
});