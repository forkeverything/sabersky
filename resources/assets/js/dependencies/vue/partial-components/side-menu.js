Vue.component('side-menu', {
    name: 'sideMenu',
    el: function () {
        return '#side-menu'
    },
    data: function () {
        return {
            show: false
        };
    },
    props: [],
    computed: {},
    methods: {
    },
    events: {
        'toggle-side-menu': function() {
            this.show = !this.show;
        },
        'hide-side-menu': function() {
            this.show = false;
        }
    },
    ready: function () {
        var self = this;
        $(window).on('resize', _.debounce(function() {
            if($(window).width() > 1670) self.show = false;
        }, 50));
    }
});