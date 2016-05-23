Vue.component('report-spendings-items', spendingsReport.extend({
    name: 'ReportSpendingsForVendors',
    el: function() {
        return '#report-spendings-items'
    },
    computed: {
        dataURL: function() {
            var url = '/reports/spendings/items/currency/' + this.currencyId;
            if(this.dateMin || this.dateMax) url += '?date=' + this.dateMin + '+' + this.dateMax;
            return url;
        },
        title: function() {
            return 'Item Spendings for ' + this.currency.code;
        }
    }
}));