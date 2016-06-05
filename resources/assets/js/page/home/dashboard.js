Vue.component('dashboard',
    {
        name: 'dashboard',

        el: function () {
            return '#dashboard'
        },
        data: function () {
            return {};
        },
        props: ['user'],
        computed: {
            date: function () {
                return moment();
            }
        },
        methods: {},
        events: {},
        ready: function () {

            $(document).ready(function () {
                $.get('/user/calendar_events', function (events) {
                    $('#dashboard-calendar').fullCalendar({
                        events: events
                    })
                });
            });
        }
    });