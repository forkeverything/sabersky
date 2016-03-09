new Vue({
    name: 'Settings',
    el: '#system-settings',
    data: {
        settings: [],
        ajaxReady: true,
        roles: [],
        roleToRemove: {}
    },
    computed: {
        saveButtonText: function() {
            return this.ajaxReady ? 'Save Settings' : 'Saving...';
        }
    },
    methods: {
        saveSettings: function() {
            var self = this;
            if(self.ajaxReady) {
                self.ajaxReady = false;
                $.ajax({
                    url: '/settings',
                    method: 'POST',
                    data: self.settings,
                    success: function (data) {
                        console.log('Successfully saved settings');
                        self.ajaxReady = true;
                        flashNotify('success', 'Successfully updated settings')
                    },
                    error: function (err) {
                        console.log(err);
                        self.ajaxReady = true;
                    }
                });
            }
        },
        hasPermission: function(permission, role) {
            return _.some(role.permissions, permission);
        },
        removePermission: function(permission, role) {
            var self = this;
            $.ajax({
                url: '/api/roles/remove_permission',
                method: 'POST',
                data: {
                    role: role,
                    permission: permission
                },
                success: function(){
                    // remove role from roles
                    self.roles = _.reject(self.roles, role);
                    // modify role
                    role.permissions = _.reject(role.permissions, permission);
                    // Add role back to roles
                    self.roles.push(role)
                },
                error: function(response) {
                    // error
                    console.log('GET REQ Error!');
                    console.log(response);
                }
            });
        },
        givePermission: function(permission, role) {
            var self = this;
            $.ajax({
                url: '/api/roles/give_permission',
                method: 'POST',
                data: {
                    role: role,
                    permission: permission
                },
                success: function(){
                    self.roles = _.reject(self.roles, role);
                    role.permissions.push(permission);
                    self.roles.push(role)
                },
                error: function(response) {
                    // error
                    console.log('GET REQ Error!');
                    console.log(response);
                }
            });
        },
        addRole: function() {
            var newRole = {};
        },
        setRemoveRole: function(role) {
            this.roleToRemove = role;
        },
        removeRole: function() {
            var self = this;
            $.ajax({
                url: '/api/roles/delete',
                method: 'POST',
                data: {
                    role: self.roleToRemove
                },
                success: function(data) {
                   // success
                    self.roles = _.reject(self.roles, self.roleToRemove);
                },
                error: function(response) {
                    console.log('Request Error!');
                    console.log(response);
                }
            });
        }
    },
    ready: function() {
        var self = this;

        $.ajax({
            url: '/api/settings',
            method: 'GET',
            success: function(data) {
                self.settings = data;
            },
            error: function(err) {
                console.log(err);
            }
        });
        $.ajax({
            url: '/api/roles',
            method: 'GET',
            success: function(data) {
                self.roles = data;
            },
            error: function(err) {
                console.log(err);
            }
        });


        var $addRoleLink = $('#link-add-role');

        $addRoleLink.editable({
            type: 'text',
            mode: 'inline',
            showbuttons: false,
            placeholder: 'Position Title'
        });

        $addRoleLink.on('shown', function() {
            setTimeout(function () {
                $addRoleLink.editable('setValue', '');
            }, 0);
        });

        $addRoleLink.on('hidden', function (e, reason) {
            $addRoleLink.editable('setValue', 'Add New Role');
        });

        $addRoleLink.on('save', function(e, params) {
            $.ajax({
                url: '/api/roles',
                method: 'POST',
                data: {
                    position: params.newValue
                },
                success: function (data) {
                    self.roles.push(data);
                },
                error: function (res) {
                    console.log(res);
                }
            });
        });



    }
});