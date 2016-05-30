Vue.component('side-menu', {
    name: 'sideMenu',
    el: function () {
        return '#side-menu'
    },
    data: function () {
        return {
            show: false,
            userPopup: false,
            userInitials: '',
            companyName: '',
            finishedCompiling: false,
            expandedSection: ''
        };
    },
    props: ['user'],
    computed: {},
    methods: {
        toggleUserPopup: function() {
            this.userPopup = !this.userPopup;
        },
        expand: function(section) {
            this.expandedSection = (this.expandedSection ===  section) ? '' : section;
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
        $(document).click(function (event) {
            if (!$(event.target).closest('.user-popup').length && !$(event.target).is('.user-popup')) {
                self.userPopup = false;
            }
        });

        // When user prop is loaded
        self.$watch('user', function (user) {
            // set initials
            var names = user.name.split(' ');
            self.userInitials = names.map(function (name, index) {
                if(index === 0 || index === names.length - 1) return name.charAt(0);
            }).join('');
            // set name
            self.companyName = user.company.name;
            // set flag
            self.finishedCompiling = true;
        });
    }
});