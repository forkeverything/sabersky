Vue.component('report-spendings-projects', {
    name: 'ReportSpendingsForProjects',
    el: function() {
        return '#report-spendings-projects'
    },
    data: function() {
        return {
            currencyId: 840,
            dateMin: '',
            dateMax:''
        };
    },
    props: [],
    computed: {

    },
    methods: {

    },
    events: {

    },
    mixins: [userCompany],
    ready: function() {

    }
});