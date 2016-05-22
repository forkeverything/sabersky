Vue.component('spendings-items-chart', baseChart.extend({
    name: 'spendingsChartForItems',
    data: function() {
        return {
            mode: 'data',
            chartLabel: 'Items',
            theme: 'blue'
        };
    },
    props: ['chart-data']
}));