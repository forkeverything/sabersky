Vue.component('report-spendings-projects', spendingsReport.extend({
    name: 'ReportSpendingsForProjects',
    el: function() {
        return '#report-spendings-projects'
    },
    computed: {
        dataURL: function() {
            var url = '/reports/spendings/projects/currency/' + this.currencyId;
            if(this.dateMin || this.dateMax) url += '?date=' + this.dateMin + '+' + this.dateMax;
            return url;
        },
        title: function() {
            return 'Project Spendings for ' + this.currency.code;
        }
    }
}));