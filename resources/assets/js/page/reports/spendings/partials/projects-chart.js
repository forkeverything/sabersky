Vue.component('spendings-projects-chart', baseChart.extend({
    name: '',
    el: function() {
        return ''
    },
    data: function() {
        return {
            chartLabel: 'Projects',
            theme: 'blue'
        };
    },
    props: ['currency-id', 'date-min', 'date-max'],
    computed: {
        chartDataURL: function() {
            var url = '/reports/spendings/projects/currency/' + this.currencyId;
            if(this.dateMin || this.dateMax) url += '?date=' + this.dateMin + '+' + this.dateMax;
            return url;
        }
    },
    methods: {

    },
    events: {

    },
    ready: function() {

    }
}));