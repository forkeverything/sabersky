Vue.component('po-single-rule', {
    template: '<tr>' +
    '<td class="col-description">' +
    '{{ rule.property.label }} - {{ rule.trigger.label }} <span ' +
    'v-if="rule.trigger.has_limit">{{ formatRuleLimit(rule) }}</span>' +
    '</td>' +
    '<td class="col-approve col-controls">' +
    '<i v-if="approved" class="fa fa-check icon-check"></i>' +
    '<button type="button" class="btn btn-approve" v-if="! set && allowedUser"  @click="processRule(' + "'approve'" + ', rule)"><i class="fa fa-check"></i></button>' +
    '<i v-if="! set && allowedUser" class="fa fa-check placeholder"></i></button>' +
    '<i v-if="! approved && ! allowedUser" class="fa fa-warning"></i>' +
    '</td>' +
    '<td class="col-reject col-controls">' +
    '<i v-if="rejected" class="fa fa-close icon-close"></i>' +
    '<button type="button" class="btn btn-reject" v-if="!set && allowedUser"  @click="processRule(' + "'reject'" + ', rule)"><i class="fa fa-close"></i></button>' +
    '<i v-if="!set && allowedUser" class="fa fa-close placeholder"></i></button>' +
    '<i v-if="! rejected && ! allowedUser" class="fa fa-warning"></i>' +
    '</td>' +
    '</tr>',
    name: 'purchaseOrderSingleRule',
    data: function () {
        return {

        };
    },
    props: ['xhr', 'purchase-order', 'rule'],
    computed: {
        set: function () {
            return this.rule.pivot.approved !== null;
        },
        approved: function () {
            return this.rule.pivot.approved;
        },
        rejected: function () {
            return this.rule.pivot.approved === 0;
        },
        allowedUser: function () {
            var self = this;
            return _.findIndex(this.rule.roles, function (role) {
                    return role.id == self.user.role_id;
                }) !== -1;
        }
    },
    methods: {
        formatRuleLimit: function (rule) {
            var currencySymbol = rule.trigger.has_currency ? rule.currency.symbol : null;
            return this.formatNumber(rule.limit, this.currencyDecimalPoints, currencySymbol);
        },
        processRule: function (action, rule) {
            var self = this;

            console.log(self.xhr);
            if(self.xhr) return;

            function updatePOStatus(data) {
                self.purchaseOrder.status = data.status;
                self.purchaseOrder.pending = data.pending;
                self.purchaseOrder.approved = data.approved;
                self.purchaseOrder.rejected = data.rejected;
            }

            if (action === 'approve') {
                self.xhr = $.get('/purchase_orders/' + self.purchaseOrder.id + '/rule/' + rule.id + '/approve', function (data) {
                    rule.pivot.approved = 1;
                    updatePOStatus(data);
                    self.xhr = '';
                })
            } else {
                self.xhr = $.get('/purchase_orders/' + self.purchaseOrder.id + '/rule/' + rule.id + '/reject', function (data) {
                    rule.pivot.approved = 0;
                    updatePOStatus(data);
                    self.xhr = '';
                })
            }
        }
    },
    mixins: [numberFormatter, userCompany],
    events: {},
    ready: function () {

    }
});