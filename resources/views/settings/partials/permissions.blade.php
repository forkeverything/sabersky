<settings-permissions inline-template :roles.sync="roles" :settings-view.sync="settingsView">
    <div id="settings-permissions">
        <h2>Staff Roles & Permissions</h2>
        <p>Add new roles, then set or modify permissions for what each role can do. Changes are saved
            automatically and reflected immediately.</p>
        <div class="table-responsive wrap-table-roles visible-lg">
            <!-- Roles Table Table -->
            <table class="table table-bordered table-roles">
                <thead>
                <tr>
                    <th></th>
                    @foreach($permissions as $permission)
                        <th>{{ $permission->label }}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                <template v-for="role in roles | orderBy 'position'">
                    <tr class="role-row"
                        v-if="role.position === 'admin' "
                    >
                        <th class="capitalize">@{{ role.position }}</th>
                        @foreach($permissions as $permission)
                            <td><i class="fa fa-circle"></i></td>
                        @endforeach
                    </tr>
                    <tr class="role-row changeable" v-else>

                        <th class="capitalize removable">
                            <span @click="editRole(role)" v-show="notEditing(role)">@{{ role.position }}</span>
                            <input type="text" v-show="! notEditing(role)" v-model="editRolePosition"
                                   :class="{ 'input-editing-role': ! notEditing(role) }">
                            <span class="overlay clickable" @click="setRemoveRole(role)" v-show="notEditing(role)"><i
                                    class="fa fa-close"></i></span>
                        </th>

                        @foreach($permissions as $permission)
                            <td v-if="hasPermission({{ $permission }}, role)"
                                class="clickable td-has-permission"
                            @click="removePermission({{ $permission }}, role)"
                            >
                            <i class="fa fa-circle"></i>
                            <i class="fa fa-close"></i>
                            </td>
                            <td v-else
                                class="clickable td-no-permission"
                            @click="givePermission({{ $permission }}, role)"
                            >
                            <i class="fa fa-circle"></i>
                            </td>
                        @endforeach
                    </tr>
                </template>
                <tr class="role-add-row"
                @click="addRole">
                <td colspan="10" id="role-add-cell" class="clickable editable editable-click"><a href="#"
                                                                                                 id="link-add-role">Add
                        New Role</a></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="roles-mobile hidden-lg">
            <div class="form-group">
                <select class="form-control" id="select-settings-role">
                    <option></option>
                    @foreach($roles as $role)
                        <option value="{{ $role->position }}">{{ ucwords($role->position) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="permissions" v-show="selectedRole">
                <div class="top">
                    <span class="selected-role" @click="editRole(selectedRole)" v-show="notEditing(selectedRole)"
                    >@{{ selectedRole.position | capitalize }}</span>
                    <input type="text" v-show="! notEditing(selectedRole)" v-model="editRolePosition"
                           :class="{ 'input-editing-role': ! notEditing(selectedRole) }">

                    <span class="remove-span clickable"
                    @click="setRemoveRole(selectedRole)"
                    v-show="selectedRole && selectedRole.position !== 'admin'"
                    >
                    remove
                    </span>
                </div>
                <!--  Role Permissions Table -->
                <table class="table table-bordered table-hover">
                    <tbody>
                    @foreach($permissions as $permission)
                        <tr class="role-row changeable">
                            <td class="permission">{{ $permission->label }}</td>
                            <template v-if="selectedRole.position !== 'admin'">
                                <td v-if="hasPermission({{ $permission }}, selectedRole)"
                                    class="clickable td-has-permission"
                                @click="removePermission({{ $permission }}, selectedRole)"
                                >
                                <i class="fa fa-circle"></i>
                                <i class="fa fa-close"></i>
                                </td>
                                <td v-else
                                    class="clickable td-no-permission"
                                @click="givePermission({{ $permission }}, selectedRole)"
                                >
                                <i class="fa fa-circle"></i>
                                </td>
                            </template>
                            <td class="td-has-permission admin-permission" v-else>
                                <i class="fa fa-circle"></i>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        <modal></modal>
    </div>
</settings-permissions>