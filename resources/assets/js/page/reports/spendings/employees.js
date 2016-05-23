Vue.component('report-spendings-employees', spendingsReport.extend({
    name: 'ReportSpendingsForVendors',
    el: function() {
        return '#report-spendings-employees'
    },
    computed: {
        dataURL: function() {
            var url = '/reports/spendings/employees/currency/' + this.currencyId;
            if(this.dateMin || this.dateMax) url += '?date=' + this.dateMin + '+' + this.dateMax;
            return url;
        },
        title: function() {
            return 'Employee Spendings for ' + this.currency.code;
        }
    }
}));