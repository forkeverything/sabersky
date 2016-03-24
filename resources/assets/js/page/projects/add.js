Vue.component('projects-add-team', {
    name: 'projectAddTeam',
    el: function() {
        return '#projects-team-add'
    },
    data: function() {
        return {
            ajaxReady: true,
            roles: []
        };
    },
    props: [],
    computed: {

    },
    methods: {

    },
    events: {

    },
    ready: function() {
        var self = this;
        if(!self.ajaxReady) return;
        self.ajaxReady = false;
        $.ajax({
            url: '/api/roles',
            method: 'GET',
            success: function(data) {
               // success
               self.roles = data;
               self.ajaxReady = true;
            },
            error: function(response) {
                console.log(response);

                vueValidation(response, self);
                self.ajaxReady = true;
            }
        });
    }
});