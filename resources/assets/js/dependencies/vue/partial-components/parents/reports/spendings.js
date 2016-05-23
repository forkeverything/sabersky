var spendingsReport = Vue.extend({
    name: 'SpendingsReport',
    template: '',
    data: function() {
        return {
            currency: '',
            currencyId: 840,
            dateMin: '',
            dateMax:'',
            spendingsData: ''
        };
    },
    props: [],
    computed: {

    },
    methods: {
        fetchSpendingsData: function() {
            $.get(this.dataURL).then(function (data) {
                this.spendingsData = data;
            }.bind(this));
        },
        clearDateRange: function() {
            this.dateMin = '';
            this.dateMax = '';
        }
    },
    events: {},
    mixins: [userCompany],
    ready: function () {
        this.fetchSpendingsData();
        // Use direct watcher because the inputs are in separate, shared
        // components so we can't bind events directly on them
        this.$watch('dataURL', this.fetchSpendingsData);
    }
});
