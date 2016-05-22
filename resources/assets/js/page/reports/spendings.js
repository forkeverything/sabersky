Vue.component('report-spendings', {
    name: 'reportsSpendings',
    el: function () {
        return '#report-spendings'
    },
    data: function () {
        return {
            category: 'projects',
            categories: [
                'projects',
                'employees',
                'vendors',
                'items'
            ],
            currencyID: 840,
            chart: '',
            showZeroValues:false,
            chartType: 'bar',
            dateMin: '',
            dateMax:''
        };
    },
    props: [],
    computed: {
        url: function() {
            var url = '/reports/spendings/' + this.category + '/currency/' + this.currencyID;
            if(this.dateMin || this.dateMax) url += '?date=' + this.dateMin + '+' + this.dateMax;
            return url;
        }
    },
    methods: {
        load: function() {
            var self = this;
            this.fetchData().done(function (data) {

                // Remove 0 values from our data
                if (!self.showZeroValues) data = _.pickBy(data, function (value) {
                    return value > 0
                });

                self.render(data);
            });
        },
        fetchData: function() {
           return $.get(this.url);
        },
        changeCategory: function(category) {
            if(this.category === category) return;
            this.category = category;
        },
        render: function(data) {
            this.chart = new Chart(this.$els.canvas.getContext('2d'), {
                type: this.chartType,
                data: {
                    labels: Object.keys(data),
                    datasets: [
                        {
                            data: _.map(data, function (val) {
                                return val;
                            }),
                            label: "Spendings"
                        }
                    ]
                }
            });
        },
        reload: function() {
            if(! _.isEmpty(this.chart)) this.chart.destroy();
            this.load();
        }
    },
    events: {},
    mixins: [userCompany],
    ready: function () {

        var self = this;

        self.load();

        self.$watch('url', function () {
            self.reload();
        });
    }
});