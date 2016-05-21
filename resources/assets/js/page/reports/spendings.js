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
            spendingsData: {},
            chart: '',
            showZeroValues:false,
            chartType: 'bar'
        };
    },
    props: [],
    computed: {
        chartData: function() {
            return {
                labels: Object.keys(this.spendingsData),
                datasets: [
                    {
                        data: _.map(this.spendingsData, function(data){
                            return data;
                        }),
                        label: "Spendings"
                    }
                ]
            }
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
           return $.get('/reports/spendings/' + this.category + '/currency/' + this.currencyID);
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
    ready: function () {

        var self = this;

        self.load();

        self.$watch('currencyID', function () {
            self.reload();
        });

        self.$watch('category', function () {
            self.reload();
        });

    }
});