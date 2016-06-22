Vue.component('staff-single', {
    name: 'staffSingle',
    el: function () {
        return '#staff-single'
    },
    data: function () {
        return {
            showChangeRoleForm: false,
            changeButton: false,
            ajaxReady: true,
            newRoleId: ''
        };
    },
    props: ['staff'],
    computed: {},
    methods: {
        toggleRoleForm: function() {
            this.showChangeRoleForm = !this.showChangeRoleForm;
        }
    },
    events: {
    },
    ready: function () {
        var self = this;
    }
});