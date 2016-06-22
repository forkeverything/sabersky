Vue.component('project-single', {
    name: 'projectSingle',
    el: function() {
        return '#project-single-view'
    },
    data: function() {
        return {
            ajaxReady: true,
            teamMembers: [],
            tableHeaders: [
                {
                    label: 'Name',
                    path: ['name'],
                    sort: 'name'
                },
                {
                    label: 'Role',
                    path: ['role', 'position'],
                    sort: 'role.position'
                },
                {
                    label: 'Email',
                    path: ['email'],
                    sort: 'email'
                }
            ]
        };
    },
    props: ['project'],
    computed: {
    },
    methods: {
        removeStaff: function(staff) {
            var self = this;
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/projects/' + self.project.id + '/team/remove',
                method: 'PUT',
                data: {
                  user_id: staff.id
                },
                success: function() {
                    self.project.team_members = _.reject(self.project.team_members, staff);
                    flashNotify('success', 'Removed ' + strCapitalize(staff.name));
                    self.ajaxReady = true;
                },
                error: function(res) {
                    console.log(res);
                    self.ajaxReady = true;
                }
            })
        }
    },
    events: {
    },
    ready: function() {}
});