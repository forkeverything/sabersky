Vue.component('company-currency-selecter', {
    template: '<select v-model="id" class="themed-select" v-el:select>' +
    '<option :value="currency.id" v-for="currency in currencies">{{ currency.code }} - {{ currency.symbol }}</option>' +
    '</select>',
    name: 'selectpicker',
    props: ['currencies', 'currency-object', 'id'],
    methods: {
        getCurrency: function(currencyID) {
            return _.find(this.currencies, function(currency) {
                return currency.id == currencyID;
            });
        },
        refresh: function() {
            this.$nextTick(function() {
                // Refresh our picker UI
                $(this.$els.select).selectpicker('refresh');
                // Update manually because v-model won't catch
                var currencyID = this.id = $(this.$els.select).selectpicker('val');
                this.currencyObject = this.getCurrency(currencyID);
            });
        }
    },
    ready: function () {
        var self = this;
        $(self.$els.select).selectpicker({
            iconBase: 'fa',
            tickIcon: 'fa-check'
        }).on('changed.bs.select', function () {
            self.currencyObject = self.getCurrency($(self.$els.select).selectpicker('val'));
        });

        self.$watch('currencies', this.refresh);

        vueEventBus.$on('updated-company-currency', this.refresh);
    }
});