Vue.component('settings', {
    name: 'Settings',
    el: function () {
        return '#system-settings';
    },
    data: function () {
        return {
            ajaxReady: true,
            settingsView: 'company',
            navLinks: [
                {
                    label: 'Company',
                    section: 'company'
                },
                {
                    label: 'Permissions',
                    section: 'permissions'
                },
                {
                    label: 'Rules',
                    section: 'rules'
                }
            ],
            // Company
            company: false,
            // Permissions
            roles: [],
            roleToRemove: false,
            roleSelect: '',
            selectedRole: false,
            editingRole: false,
            editRolePosition: false,
            roleToUpdate: {},
            updatedRoleVal: '',
            // Rules
            rules: [],
            ruleProperties: [],
            selectedProperty: false,
            selectedTrigger: false,
            selectedRuleRoles: [],
            ruleLimit: '',
            ruleToRemove: false
        }
    },
    computed: {
        ruleHasLimit: function () {
            return (this.selectedTrigger && this.selectedTrigger.has_limit);
        },
        canSubmitRule: function () {
            if (this.ruleHasLimit) {
                return this.selectedProperty && this.selectedTrigger && this.selectedRuleRoles.length > 0 && this.ruleLimit > 0;
            }
            return this.selectedProperty && this.selectedTrigger && this.selectedRuleRoles.length > 0;
        },
        hasRules: function () {
            return !_.isEmpty(this.rules);
        },
        canUpdateCompany: function () {
            if (this.company) {
                return this.company.name.length > 0 && this.company.description.length > 0 && this.company.currency.length > 0;
            }
            return false;
        }
    },
    methods: {
        changeView: function (view) {
            this.settingsView = view;
        },
        // Permissions
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
            this.$broadcast('new-modal', {
                title: 'Permanently Remove ' + strCapitalize(role.position),
                body: "Removing a role is irreversible. Any team members that have those roles will lose all their permissions and won't be able to complete any tasks until you assign them a new role.",
                buttonClass: 'btn-danger',
                buttonText: 'remove',
                callbackEventName: 'remove-role'
            });
            this.roleToRemove = role;
        },
        removeRole: function () {
            var self = this;
            if (!self.ajaxReady) return;
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
        editRole: function (role) {
            var self = this;
            self.editingRole = role;
            self.editRolePosition = role.position;
            self.$nextTick(function () {
                var $inputEdit = $('.input-editing-role');
                $inputEdit.focus();
                var blurFired = false; // blur fired flag
                $inputEdit.keypress(function (e) {
                    if (e.which == 13) {
                        this.blur();
                    }
                });
                $inputEdit.blur(function () {
                    var newRoleVal = $inputEdit.val().toLowerCase();
                    if (blurFired) return;
                    blurFired = true;
                    if (newRoleVal !== role.position && newRoleVal.length !== 0) {
                        self.confirmEdit(role, newRoleVal);
                    }
                    self.editingRole = false;
                    self.editRolePosition = false;

                });
            });

        },
        notEditing: function (role) {
            return role !== this.editingRole;
        },
        confirmEdit: function (oldRole, newRoleVal) {
            this.roleToUpdate = oldRole;
            this.updatedRoleVal = newRoleVal;
            this.$broadcast('new-modal', {
                title: 'Confirm Edit ' + strCapitalize(this.editingRole.position) + ' to ' + strCapitalize(newRoleVal),
                body: 'Role changes are immediate and will automatically effect all team members that have the role.',
                buttonClass: 'btn-primary',
                buttonText: 'update',
                callbackEventName: 'update-role'
            });
        },
        updateRole: function () {
            var self = this;
            $.ajax({
                url: '/api/roles/' + self.roleToUpdate.id,
                method: 'PUT',
                data: {
                    role: self.roleToUpdate,
                    newPosition: self.updatedRoleVal
                },
                success: function (role) {
                    self.roles = _.reject(self.roles, self.roleToUpdate);
                    self.roles.push(role);

                    self.roleSelect.updateOption(self.roleToUpdate.position, {
                        value: role.position,
                        text: strCapitalize(role.position)
                    });

                    // select new option
                    if (self.selectedRole.position === self.roleToUpdate.position) self.selectedRole = role;
                },
                error: function (response) {
                    console.log('Request Error!');
                    console.log(response);
                }
            });
        },
        setTriggers: function () {
            this.selectedTrigger = '';
        },
        addRule: function () {
            var self = this;
            var postData = {
                rule_property_id: self.selectedProperty.id,
                rule_trigger_id: self.selectedTrigger.id,
                limit: self.ruleLimit,
                roles: self.selectedRuleRoles
            };
            $.ajax({
                url: '/api/rules',
                method: 'POST',
                data: postData,
                success: function (data) {
                    // success
                    self.fetchRules();
                    flashNotify('success', 'Successfully added a new Rule');
                    self.resetRuleValues();
                },
                error: function (response) {
                    console.log('Request Error!');
                    console.log(response);
                    self.resetRuleValues();
                    if (response.status === 409) {
                        flashNotify('error', 'Rule already exists');
                    } else {
                        flashNotify('error', 'Could not add Rule');
                    }

                }
            });
        },
        resetRuleValues: function () {
            this.ruleLimit = '';
            this.selectedRuleRoles = [];
        },
        setRemoveRule: function (rule) {
            this.ruleToRemove = rule;
            this.$broadcast('new-modal', {
                title: 'Confirm Remove Rule',
                body: "Removing a rule is irreversible. Any Pending (Unapproved) Purchase Orders that is waiting for the Rule to be approved may automatically be approved for processing.",
                buttonClass: 'btn-danger',
                buttonText: 'remove',
                callbackEventName: 'remove-rule'
            });
        },
        removeRule: function () {
            var self = this;
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/api/rules/' + self.ruleToRemove.id + '/remove',
                method: 'DELETE',
                success: function (data) {
                    // success
                    self.fetchRules();
                    self.ajaxReady = true;
                },
                error: function (response) {
                    console.log('Request Error!');
                    console.log(response);
                    self.ajaxReady = true;
                }
            });
        },
        fetchRules: function () {
            var self = this;
            $.ajax({
                url: '/api/rules',
                method: 'GET',
                success: function (data) {
                    // success
                    self.rules = data;
                },
                error: function (response) {
                    console.log('Request Error!');
                    console.log(response);
                }
            });
        },
        updateCompany: function () {
            var self = this;
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/api/company',
                method: 'PUT',
                data: {
                    name: self.company.name,
                    description: self.company.description,
                    currency: self.company.currency
                },
                success: function (data) {
                    // success
                    flashNotify('success', 'Updated Company information');
                    self.$dispatch('update-currency', self.company.currency);
                    self.ajaxReady = true;
                },
                error: function (response) {
                    console.log('Request Error!');
                    console.log(response);
                    vueValidation(response, self);
                    self.ajaxReady = true;
                }
            });
        }
    },
    ready: function () {
        var self = this;

        // GET user company info
        $.ajax({
            url: '/api/company',
            method: 'GET',
            success: function (data) {
                // success
                self.company = data;
            },
            error: function (response) {
                console.log('Request Error!');
                console.log(response);
            }
        });

        // GET company roles
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
            if (!self.ajaxReady) return;
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

            saveRole(value, function () {
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

        $.ajax({
            url: '/api/rules/properties_triggers',
            method: 'GET',
            success: function (data) {
                // success
                self.ruleProperties = data;
            },
            error: function (response) {
                console.log('Request Error!');
                console.log(response);
            }
        });

        self.fetchRules();
    },
    events: {
        'remove-role': function() {
            this.removeRole();
        },
        'remove-rule': function() {
            this.removeRule();
        },
        'update-role': function() {
            this.updateRole();
        }
    }
});
