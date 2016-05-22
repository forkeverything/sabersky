Vue.component('spendings-employees-chart', baseChart.extend({
    name: 'spendingsChartForEmployees',
    data: function() {
        return {
            mode: 'data',
            chartLabel: 'Employees',
            theme: 'green'
        };
    },
    props: ['chart-data']
}));