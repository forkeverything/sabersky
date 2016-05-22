Vue.component('spendings-projects-chart', baseChart.extend({
    name: 'spendingsChartForProjects',
    data: function() {
        return {
            mode: 'data',
            chartLabel: 'Projects',
            theme: 'blue'
        };
    },
    props: ['chart-data']
}));