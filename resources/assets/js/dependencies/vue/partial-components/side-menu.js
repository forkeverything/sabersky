Vue.component('side-menu', {
    name: 'sideMenu',
    el: function () {
        return '#side-menu'
    },
    data: function () {
        return {
            show: false,
            userPopup: false
        };
    },
    props: [],
    computed: {
        userInitials: function() {
            return this.$root.user.name.split(' ').map(function (name) {
                return name.charAt(0);
            }).join('');
        }
    },
    methods: {
        toggleUserPopup: function() {
            this.userPopup = !this.userPopup;
        }
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

        // To hide popup
        $(document).click(function(event) {
            if(!$(event.target).closest('.user-popup').length &&
                !$(event.target).is('.user-popup')) {
                self.userPopup = false;
            }
        })
    }
});