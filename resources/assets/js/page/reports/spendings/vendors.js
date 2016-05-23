Vue.component('report-spendings-vendors', spendingsReport.extend({
    name: 'ReportSpendingsForVendors',
    el: function() {
        return '#report-spendings-vendors'
    },
    computed: {
        dataURL: function() {
            var url = '/reports/spendings/vendors/currency/' + this.currencyId;
            if(this.dateMin || this.dateMax) url += '?date=' + this.dateMin + '+' + this.dateMax;
            return url;
        },
        title: function() {
            return 'Vendor Spendings for ' + this.currency.code;
        }
    }
}));