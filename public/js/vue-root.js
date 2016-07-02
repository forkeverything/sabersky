// Root script that gets run on EVERY page

// Initialize our event bus
var vueEventBus = new Vue();

// root Vue instance
new Vue({
    el: '#app-layout',
    data: {
        xhr: '',    // store currently happening request - like a shared ajaxReady
        user: {
            photo: '',
            company: {
                address: {},
                settings: {
                    currency: {}
                }
            }
        },
        showingMenu: false,
        showNavDropdown: false
    },
    events: {
        'update-company': function () {
            this.getLoggedUser();
        }
    },
    methods: {
        getLoggedUser: function() {
          var self = this;
            $.get('/user', function (data) {
                self.user = data;
            });
        },
        toggleSideMenu: function () {
            this.$broadcast('toggle-side-menu');
            this.showingMenu = !this.showingMenu;
        },
        hideOverlays: function() {
            this.$broadcast('hide-side-menu');
            this.showingMenu = false;
            this.showNavDropdown = false;
        },
        toggleNavDropdown: function() {
            this.showNavDropdown = !this.showNavDropdown;
        },
        getStarted: function() {
            vueEventBus.$emit('clicked-join-button');
        }
    },
    ready: function () {
        this.getLoggedUser();
    }
});

/**
 TODO :: Find a way to persist user info
 on Local Storage so that we aren't requesting it all the time.

 Problem - When to force browser to clear / refresh LS? Need
 to ensure it is consistent with our server data.

 Possible - Flush / Load on login / logout.
 **/