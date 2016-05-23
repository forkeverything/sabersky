Vue.component('projects-add-team', {
    name: 'projectAddTeam',
    el: function() {
        return '#projects-team-add'
    },
    data: function() {
        return {
            ajaxReady: true,
            existingUserId: '',
            newUserName: '',
            newUserEmail: '',
            newUserRoleId: ''
        };
    },
    props: ['project'],
    computed: {

    },
    methods: {
        addTeamMember: function() {
            var self = this;
            vueClearValidationErrors(self);
            if(!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/projects/' + self.project.id + '/team/add',
                method: 'POST',
                data: {
                    "existing_user_id": self.existingUserId,
                    "name": self.newUserName,
                    "email": self.newUserEmail,
                    "role_id": self.newUserRoleId
                },
                success: function(data) {
                   // success

                   self.ajaxReady = true;
                },
                error: function(response) {
                    console.log(response);

                    vueValidation(response, self);
                    self.ajaxReady = true;
                }
            });
        }
    },
    events: {

    },
    ready: function() {
    }
});