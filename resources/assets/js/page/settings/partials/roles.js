Vue.component('settings-roles', {
    name: 'settingsRoles',
    el: function() {
        return '#settings-roles'
    },
    data: function() {
        return {
            ajaxReady: true,
            showAddNewRoleForm: false,
            showModal: false,
            editingRole: false,
            selectedRole: '',
            newRole: ''
        };
    },
    props: ['roles', 'permissions'],
    computed: {

    },
    methods: {
        toggleAddNewRoleForm: function() {
            this.newRole = '';
            this.showAddNewRoleForm = !this.showAddNewRoleForm;
        },
        addRole: function() {
            var self = this;
            if(!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/api/roles',
                method: 'POST',
                data: {
                    position: self.newRole
                },
                success: function (data) {
                    self.roles.push(data);
                    self.newRole = '';
                    flashNotify('success', 'Saved new role');
                    self.ajaxReady = true;
                    self.toggleAddNewRoleForm();
                },
                error: function (res) {
                    flashNotify('error', 'Could not add role');
                    console.log(res);
                    self.ajaxReady = true;
                }
            });
        },
        launchRoleModal: function(role) {
            this.selectedRole = role;
            this.showModal = true;
        },
        hideModal: function() {
            this.showModal = false;
        },
        enterEditMode: function() {
            this.editingRole = true;
            this.$nextTick(function () {
                $(this.$els.inputRole).focus();
            }.bind(this));
        },
        exitEditMode: function() {
            var self = this;
            $.ajax({
                url: '/api/roles/' + self.selectedRole.id,
                method: 'PUT',
                data: {
                    role: self.selectedRole,
                    newPosition: self.selectedRole.position
                },
                success: function () {
                    self.editingRole = false;
                },
                error: function (response) {
                    console.log('Request Error!');
                    console.log(response);
                }
            });
        },
        hasPermission: function (permission, role) {
            return _.some(role.permissions, permission);
        },
        removePermission: function (permission, role) {
            var self = this;
            $.ajax({
                url: '/api/roles/remove_permission',
                method: 'POST',
                data: {
                    role: role,
                    permission: permission
                },
                success: function () {
                    // remove role from roles
                    self.roles = _.reject(self.roles, role);
                    // modify role
                    role.permissions = _.reject(role.permissions, permission);
                    // Add role back to roles
                    self.roles.push(role);
                },
                error: function (response) {
                    // error
                    console.log('GET REQ Error!');
                    console.log(response);
                }
            });
        },
        givePermission: function (permission, role) {
            var self = this;
            $.ajax({
                url: '/api/roles/give_permission',
                method: 'POST',
                data: {
                    role: role,
                    permission: permission
                },
                success: function () {
                    self.roles = _.reject(self.roles, role);
                    role.permissions.push(permission);
                    self.roles.push(role)
                },
                error: function (response) {
                    // error
                    console.log('GET REQ Error!');
                    console.log(response);
                }
            });
        },
        removeRole: function() {
            var self = this;
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/api/roles/' + self.selectedRole.id,
                method: 'DELETE',
                success: function (data) {
                    // success
                    self.roles = _.reject(self.roles, self.selectedRole);
                    self.showModal = false;
                    flashNotify('success', 'Removed role: ' + self.selectedRole.position);
                    self.selectedRole = '';
                    self.ajaxReady = true;
                },
                error: function (response) {
                    console.log('Error removing Role');
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