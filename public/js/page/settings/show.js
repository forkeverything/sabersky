new Vue({
    name: 'Settings',
    el: '#system-settings',
    data: {
        settings: [],
        saveButtonText: 'Save Settings',
        ajaxReady: true,
        roles: [],
        roleToRemove: false,
        roleSelect: '',
        selectedRole: false,
        editingRole: false,
        editRolePosition: false,
        roleToUpdate: {},
        updatedRoleVal: '',
        modalTitle: '',
        modalBody: '',
        modalMode: ''
    },
    methods: {
        saveSettings: function () {
            var self = this;
            if (! self.ajaxReady) return;
                self.saveButtonText = 'Saving...';
                self.ajaxReady = false;
                $.ajax({
                    url: '/settings',
                    method: 'POST',
                    data: self.settings,
                    success: function (data) {
                        console.log('Successfully saved settings');
                        self.ajaxReady = true;
                        flashNotify('success', 'Successfully updated settings')
                        self.saveButtonText =  'Save Settings';
                    },
                    error: function (err) {
                        console.log(err);
                        self.ajaxReady = true;
                        self.saveButtonText =  'Save Settings';
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
        addRole: function () {
            var newRole = {};
        },
        setRemoveRole: function (role) {
            this.modalTitle = 'Permanently Remove ' + strCapitalize(role.position);
            this.modalBody = "Removing a role is irreversible. Any team members that have those roles will lose all their permissions and won't be able to complete any tasks until you assign them a new role.";
            this.modalMode = 'remove';
            this.roleToRemove = role;
        },
        removeRole: function () {
            var self = this;
            if(! self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/api/roles/delete',
                method: 'POST',
                data: {
                    role: self.roleToRemove
                },
                success: function (data) {
                    // success
                    self.roles = _.reject(self.roles, self.roleToRemove);
                    // Remove from selectbox
                    self.roleSelect.removeOption(self.roleToRemove.position);
                    self.roleSelect.removeItem(self.roleToRemove.position, false);
                    self.selectedRole = false;
                    self.ajaxReady = true;
                },
                error: function (response) {
                    console.log('Request Error!');
                    console.log(response);
                    self.ajaxReady = true;
                }
            });
        },
        editRole: function(role){
            var self = this;
            self.editingRole = role;
            self.editRolePosition = role.position;
            self.$nextTick(function () {
                var $inputEdit = $('.input-editing-role');
                $inputEdit.focus();
                var blurFired = false; // blur fired flag
                $inputEdit.keypress(function(e) {
                    if(e.which == 13) {
                        this.blur();
                    }
                });
                $inputEdit.blur(function () {
                    var newRoleVal = $inputEdit.val().toLowerCase();
                    if(blurFired) return;
                    blurFired = true;
                    if(newRoleVal !== role.position && newRoleVal.length !== 0) {
                        self.confirmEdit(role, newRoleVal);
                    }
                        self.editingRole = false;
                        self.editRolePosition = false;

                });
            });

        },
        notEditing: function(role) {
            return role !== this.editingRole;
        },
        confirmEdit: function(oldRole, newRoleVal) {
            this.modalTitle = 'Confirm Edit ' + strCapitalize(this.editingRole.position) + ' to ' + strCapitalize(newRoleVal);
            this.modalBody = 'Role changes are immediate and will automatically effect all team members that have the role.';
            this.modalMode = 'update';
            this.roleToUpdate = oldRole;
            this.updatedRoleVal = newRoleVal;
            $('#modal-confirm').modal('show');
        },
        updateRole: function() {
            var self = this;
            $.ajax({
                url: '/api/roles/' + self.roleToUpdate.id,
                method: 'PUT',
                data: {
                    role: self.roleToUpdate,
                    newPosition: self.updatedRoleVal
                },
                success: function(role) {
                    self.roles = _.reject(self.roles, self.roleToUpdate);
                    self.roles.push(role);

                    self.roleSelect.updateOption(self.roleToUpdate.position, {
                        value: role.position,
                        text: strCapitalize(role.position)
                    });

                    // select new option
                    if(self.selectedRole.position === self.roleToUpdate.position) self.selectedRole = role;
                },
                error: function(response) {
                    console.log('Request Error!');
                    console.log(response);
                }
            });
        }
    },
    ready: function () {
        var self = this;

        $.ajax({
            url: '/api/settings',
            method: 'GET',
            success: function (data) {
                self.settings = data;
            },
            error: function (err) {
                console.log(err);
            }
        });

        $.ajax({
            url: '/api/roles',
            method: 'GET',
            success: function (data) {
                self.roles = data;
            },
            error: function (err) {
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

        $addRoleLink.on('shown', function () {
            setTimeout(function () {
                $addRoleLink.editable('setValue', '');
            }, 0);
        });

        $addRoleLink.on('hidden', function (e, reason) {
            $addRoleLink.editable('setValue', 'Add New Role');
        });

        function saveRole(position, successFn, errorFn) {
            if (! self.ajaxReady) return;
                self.ajaxReady = false;
                $.ajax({
                    url: '/api/roles',
                    method: 'POST',
                    data: {
                        position: position
                    },
                    success: function (data) {
                        self.roles.push(data);
                        successFn ? successFn() : null;
                        self.ajaxReady = true;
                    },
                    error: function (res) {
                        console.log('Error: saving new role');
                        console.log(res);
                        errorFn ? errorFn() : null;
                        self.ajaxReady = true;
                    }
                });
        }

        self.roleSelect = uniqueSelectize('#select-settings-role', 'Select or type to add a new role');

        $addRoleLink.on('save', function (e, params) {
            self.roleSelect.addOption({
                value: params.newValue,
                text: params.newValue
            });
        });

        self.roleSelect.on("option_add", function (value, $item) {
            self.roleSelect.updateOption(value, {
                value: value,
                text: strCapitalize(value)
            });

            saveRole(value, function() {
                // success
                self.selectedRole = _.find(self.roles, {position: value});
            }, function () {
                // error:
                self.roleSelect.removeOption(value);
            });
        });

        self.roleSelect.on("item_add", function (value, $item) {
            self.selectedRole = _.find(self.roles, {position: value});
        });



    }
});