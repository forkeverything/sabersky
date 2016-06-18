Vue.component('staff-all', {
    name: 'staffAll',
    el: function() {
        return '#staff-all'
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
            url: '/api/staff',
            method: 'GET',
            success: function(data) {
               // success
               self.employees = _.map(data, function(staff) {
                   staff.name = '<a href="/staff/' + staff.id + '">' + staff.name + '</a>';
                   staff.status = staff.invite_key ? 'Pending' : 'Confirmed';
                   return staff;
               });
            },
            error: function(response) {
                console.log(response);
            }
        });
    }
});