Vue.component('spendings-vendors-chart', baseChart.extend({
    name: 'spendingsChartForVendors',
    data: function() {
        return {
            mode: 'data',
            chartLabel: 'Vendors',
            theme: 'red'
        };
    },
    props: ['chart-data']
}));