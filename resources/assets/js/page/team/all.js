Vue.component('team-all', {
    name: 'teamAll',
    el: function() {
        return '#team-all'
    },
    data: function() {
        return {
            employees: [],
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
                },
                {
                    label: 'Status',
                    path: ['status'],
                    sort: 'status'
                }
            ]
        };
    },
    props: ['user'],
    computed: {
        
    },
    methods: {
        
    },
    events: {
        
    },
    ready: function() {
        var self = this;
        $.ajax({
            url: '/api/team',
            method: 'GET',
            success: function(data) {
               // success
               self.employees = _.map(data, function(staff) {
                   staff.status = staff.invite_key ? '<span class="badge badge-warning">Pending</span>' : '<span class="badge badge-success">Confirmed</span>';
                   return staff;
               });
            },
            error: function(response) {
                console.log(response);
            }
        });
    }
});