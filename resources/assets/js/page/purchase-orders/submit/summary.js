Vue.component('po-submit-summary', {
    name: 'summary',
    template: '<div class="summary table-responsive">' +
    '<table class="table table-standard table-summary">' +
    '<tbody>' +
    '<tr>' +
    '<td class="col-title">Subtotal</td>' +
    '<td class="col-amount">{{ formatNumber(orderSubtotal, currencyDecimalPoints) }}</td>' +
    '<td class="col-currency">{{ currencySymbol }}</td>' +
    '</tr>' +
    '<template v-for="cost in additionalCosts">' +
    '<tr class="row-added-costs">' +
    '<td class="col-title">' +
    '{{ cost.name }}' +
    '<button type="button" class="close" aria-label="Close" @click="removeAdditionalCost(cost)"><span aria-hidden="true">&times;</span></button>' +
    '</td>' +
    '<td class="col-amount">{{ formatNumber(cost.amount, currencyDecimalPoints) }}</td>' +
    '<td class="col-currency">{{ cost.type }}</td>' +
    '</tr>' +
    '</template>' +
    '<tr class="row-inputs">' +
    '<td class="col-title">' +
    '<input type="text" class="form-control" placeholder="cost / discount" v-model="newCost.name">' +
    '</td>' +
    '<td class="col-amount">' +
    '<number-input :model.sync="newCost.amount" :placeholder="' + "'amount'" + '" :class="[' + "'form-control'" + ']"></number-input>' +
    '</td>' +
    '<td class="col-currency">' +
    '<select-picker :options="[{value:' + "'%', label: '%'" + '}, {value: currencySymbol, label: currencySymbol }]" :name.sync="newCost.type"></select-picker>' +
    '</td>' +
    '</tr>' +
    '<tr v-show="canAddNewCost" class="row-add-button">' +
    '<td></td>' +
    '<td></td>' +
    '<td>' +
    '<button type="button" class="btn btn-small btn-add-cost btn-outline-blue" @click="addAdditionalCost"><i class="fa fa-plus"></i> Cost / Discount</button></td>' +
    '</tr>' +
    '<tr class="row-total">' +
    '<td class="col-title">Total Cost</td>' +
    '<td class="col-amount">{{ formatNumber(orderTotal, currencyDecimalPoints) }}</td>' +
    '<td class="col-currency">{{ currencySymbol }}</td>' +
    '</tr>' +
    '</tbody>' +
    '</table>' +
    '</div>',
    data: function () {
        return {
            newCost: {
                name: '',
                type: '%',
                amonut: ''
            }
        };
    },
    props: ['line-items', 'additional-costs', 'currency-symbol', 'currency-decimal-points'],
    computed: {
        orderSubtotal: function () {
            var self = this;
            var subtotal = 0;
            if (!self.lineItems.length > 0) return;
            _.forEach(self.lineItems, function (item) {
                if (item.order_quantity && item.order_price && isNumeric(item.order_quantity) && isNumeric(item.order_price)) subtotal += (item.order_quantity * item.order_price);
            });
            return subtotal;
        },
        canAddNewCost: function () {
            return this.newCost.name && this.newCost.amount && this.newCost.type;
        },
        orderTotal: function () {
            var subtotal = this.orderSubtotal;
            var total = subtotal;
            _.forEach(this.additionalCosts, function (cost) {
                var amount = parseFloat(cost.amount);
                if (cost.type == '%') {

                    // Calculate the percentage off the sub-total NOT running total. This implies
                    // that other additional costs are NOT taxable. If user wants to include
                    // taxable costs, add as separate additional costs / discounts.

                    total += (subtotal * amount / 100);
                } else {
                    total += amount;
                }
            });
            return total;
        }
    },
    methods: {
        removeAdditionalCost: function (cost) {
            this.additionalCosts = _.reject(this.additionalCosts, cost);
        },
        addAdditionalCost: function () {
            this.additionalCosts.push(this.newCost);
            this.newCost = {
                name: '',
                type: '%',
                amonut: ''
            }
        }
    },
    events: {},
    mixins: [numberFormatter],
    ready: function () {
    }
});