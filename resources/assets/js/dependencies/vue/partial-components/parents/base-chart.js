var baseChart = Vue.extend({
    name: 'BaseChart',
    template: '<canvas v-el:canvas class="canvas-chart"></canvas>',
    data: function () {
        return {
            mode: 'url',
            chartLabel: '',
            showZeroValues: false,
            chartType: 'bar',
            chart: '',
            theme: 'red'
        }
    },
    props: [],
    computed: {
        colors: function() {
            switch(this.theme) {
                case 'red':
                    return {
                        backgroundColor: "rgba(255,99,132,0.2)",
                        borderColor: "rgba(255,99,132,1)",
                        hoverBackgroundColor: "rgba(255,99,132,0.4)",
                        hoverBorderColor: "rgba(255,99,132,1)"
                    };
                    break;
                case 'blue':
                    return {
                        backgroundColor: "rgba(52,152,219,0.2)",
                        borderColor: "rgba(52,152,219,1)",
                        hoverBackgroundColor: "rgba(52,152,219,0.4)",
                        hoverBorderColor: "rgba(52,152,219,1)"
                    };
                    break;
                case 'green':
                    return {
                        backgroundColor: "rgba(46,204,113,0.2)",
                        borderColor: "rgba(46,204,113,1)",
                        hoverBackgroundColor: "rgba(46,204,113,0.4)",
                        hoverBorderColor: "rgba(46,204,113,1)"
                    };
                    break;
                default:
                    break;
            }

        },
        backgroundColor: function() {
            return this.colors.backgroundColor;
        },
        borderColor: function(){
            return this.colors.borderColor;
        },
        hoverBackgroundColor: function() {
            return this.colors.hoverBackgroundColor;
        },
        hoverBorderColor: function() {
            return this.colors.hoverBorderColor;
        }
    },
    methods: {
        load: function () {
            var self = this;

            if(this.mode === 'url') {
                this.fetchData().done(function (data) {
                    self.render(data);
                });
            }
            self.render(this.chartData);
        },
        fetchData: function () {
            return $.get(this.chartURL);
        },
        render: function (data) {

            // Remove 0 values from our data
            if (!this.showZeroValues) data = this.removeZeroValues(data);

            this.chart = new Chart(this.$els.canvas.getContext('2d'), {
                type: this.chartType,
                data: {
                    labels: Object.keys(data),
                    datasets: [
                        {
                            data: _.map(data, function (val) {
                                return val;
                            }),
                            label: this.chartLabel,
                            backgroundColor: this.backgroundColor,
                            borderColor: this.borderColor,
                            borderWidth: 1,
                            hoverBackgroundColor: this.hoverBackgroundColor,
                            hoverBorderColor: this.hoverBorderColor
                        }
                    ]
                }
            });
        },
        removeZeroValues: function(data) {
            return _.pickBy(data, function (value) {
                return value > 0
            });
        },
        reload: function () {
            if (!_.isEmpty(this.chart)) this.chart.destroy();
            this.load();
        }
    },
    events: {},
    ready: function () {
        if(this.mode === 'url' && !this.chartURL) throw new Error("Chart Mode: url - no URL to retrieve chart data");

        var watchVariable = this.mode === 'url' ? 'chartURL' : 'chartData';

        this.$watch(watchVariable, function () {
            this.reload();
        }.bind(this));

        this.load();


    }
});