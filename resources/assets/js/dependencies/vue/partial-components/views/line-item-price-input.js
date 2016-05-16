Vue.component('line-item-price-input', {
    name: 'lineItemPriceInput',
    template: '<input type="text" class="input-price form-control" v-model="inputVal" placeholder="price" @change="updateOtherLineItemPrices()">',
    props: ['model', 'line-items', 'current-line-item', 'decimal'],
    computed: {
        precision: function() {
            return this.decimal || 0;
        },
        inputVal: {
            get: function() {
                if(this.model === 0) return 0;
                if(! this.model) return;
                return accounting.formatNumber(this.model, this.precision, ",");
            },
            set: function(newVal) {
                // Acts like a 2 way filter
                var decimal = this.decimal || 0;
                this.model = accounting.toFixed(newVal, this.precision);
            }
        }
    },
    methods: {
        updateOtherLineItemPrices: function () {
            console.log('changed!');

            var self = this;

            var otherLineItemsWithSameItem = _.filter(self.lineItems, function (lineItem) {
                return lineItem.item.id === self.currentLineItem.item.id;
            });

            console.log(otherLineItemsWithSameItem);

            _.forEach(otherLineItemsWithSameItem, function (lineItem) {

                if(lineItem.id === self.currentLineItem.id) return;

                var index = _.indexOf(self.lineItems, lineItem);
                console.log('index is: ' + index);

                var updatedLineItem = lineItem;
                updatedLineItem.order_price = self.currentLineItem.order_price;
                console.log('updated price is: ' + updatedLineItem.order_price);

                self.lineItems.splice(index, 1, updatedLineItem);

            });
        }
    },
    ready: function() {
    }
});