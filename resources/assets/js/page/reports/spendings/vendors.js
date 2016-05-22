Vue.component('report-spendings-vendors', {
    name: 'ReportSpendingsForVendors',
    el: function() {
        return '#report-spendings-vendors'
    },
    data: function() {
        return {
            currencyId: 840,
            dateMin: '',
            dateMax:'',
            spendingsData: ''
        };
    },
    props: [],
    computed: {
        dataURL: function() {
            var url = '/reports/spendings/vendors/currency/' + this.currencyId;
            if(this.dateMin || this.dateMax) url += '?date=' + this.dateMin + '+' + this.dateMax;
            return url;
        }
    },
    methods: {
        fetchSpendingsData: function() {
            $.get(this.dataURL).then(function (data) {
                this.spendingsData = data;
            }.bind(this));
        }
    },
    events: {

    },
    mixins: [userCompany],
    ready: function() {
        this.fetchSpendingsData();
        // Use direct watcher because the inputs are in separate, shared
        // components so we can't bind events directly on them
        this.$watch('dataURL', this.fetchSpendingsData);
    }
});