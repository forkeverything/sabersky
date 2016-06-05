Vue.component('dashboard',
    {
    name: 'dashboard',

    el: function() {
        return '#dashboard'
    },
    data: function() {
        return {

        };
    },
    props: ['user'],
    computed: {
        date: function() {
            return moment();
        }
    },
    methods: {

    },
    events: {

    },
    ready: function() {
        
    }
});