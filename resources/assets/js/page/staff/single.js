Vue.component('staff-single', {
    name: 'staffSingle',
    el: function () {
        return '#staff-single'
    },
    data: function () {
        return {
            roles: [],
            changeButton: false,
            ajaxReady: true
        };
    },
    props: [],
    computed: {},
    methods: {
        showChangeButton: function () {
            this.changeButton = true;
        }
    },
    events: {
    },
    ready: function () {
        var self = this;
    }
});