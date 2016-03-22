new Vue({
    el: '#app-layout',
    data: {
        company: {}
    },
    events: {
        'update-company': function() {
            this.getCompanyInfo();
        }
    },
    methods: {
      getCompanyInfo: function() {
          var self = this;
          $.ajax({
              url: '/api/company',
              method: 'GET',
              success: function(data) {
                  self.company = data;
              },
              error: function(response) {
                  console.log('Could not fetch user company');
              }
          });
      }
    },
    ready: function () {
        this.getCompanyInfo();
    }
});

/**
 TODO :: Find a way to persist client company on Local Storage
 so that we aren't requesting it all the time.

 Problem - When to force browser to clear / refresh LS? Need
 to ensure it is consistent with our server data.

 Possible - Flush / Load on login / logout.
 **/