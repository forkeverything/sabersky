new Vue({
    el: '#app-layout',
    data: {
        user: {},
        company: {},
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
          $.ajax({
              url: '/api/me',
              method: 'GET',
              success: function(data) {
                  self.user = data;
              },
              error: function(response) {
                  console.log('No logged user');
              }
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