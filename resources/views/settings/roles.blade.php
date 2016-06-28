@extends('settings.partials.layout')

@section('settings-header')
    <h1>Settings - Roles</h1>
    <p>
        Define staff roles and their permissions for your company. Every registered staff member of your company must be
        assigned a role, which determines what they can do in the app. You can create or edit roles freely but you
        cannot delete roles with staff members assigned to it.
    </p>
@endsection
@section('settings-content')


    <settings-roles inline-template :roles="{{ $roles }}" :permissions="{{ $permissions }}">
        <div>
            <div id="settings-roles">

                <div class="part">
                    <button type="button" class="btn btn-outline-blue" @click="toggleAddNewRoleForm" v-show="
                    ! showAddNewRoleForm">Add New Role</button>
                    <form id="form-add-role" @submit.prevent="addRole" v-show="showAddNewRoleForm">
                        <div class="form-group">
                            <label>Position</label>
                            <input type="text" v-model="newRole" class="form-control">
                        </div>
                        <div class="form-group align-end">
                            <button type="button" class="btn btn-outline-grey btn-cancel" @click="toggleAddNewRoleForm">
                            Cancel</button>
                            <button type="submit" class="btn btn-solid-green">Save</button>
                        </div>
                    </form>
                </div>

                <!-- Roles Table -->
                <table class="table table-hover table-standard">
                    <thead>
                    <tr>
                        <th class="heading-center">Position</th>
                        <th class="heading-center">assigned staff</th>
                    <tr>
                    </thead>
                    <tbody>
                    <template v-for="role in roles">
                        <tr class="clickable" @click="launchRoleModal(role)">
                        <td class="fit-to-content content-center">@{{ role.position }}</td>
                        <td class="fit-to-content content-center">@{{ role.users.length }}</td>
                        </tr>
                    </template>
                    </tbody>
                </table>


                <div id="role-modal" class="modal-overlay" v-show="showModal" @click="hideModal">
                <div class="modal-body" @click.stop="">
                    <button type="button" @click="hideModal" class="btn button-hide-modal"><i
                            class="fa fa-close"></i></button>

                    <h2 class="capitalize" v-show="! editingRole">@{{ selectedRole.position }}</h2>

                    <input id="modal-input-role-position" type="text" v-model="selectedRole.position"
                           class="form-control" v-show="editingRole" v-el:input-role @blur="exitEditMode"
                           @keyup.enter="exitEditMode">

                    <div class="buttons" v-if="selectedRole.position !== 'admin'">
                        <button type="button" class="btn btn-small btn-outline-blue" @click="enterEditMode" v-show="
                        ! editingRole">Edit</button>
                        <button type="button" class="btn btn-small btn-solid-red"
                                v-if="selectedRole.users && selectedRole.users.length === 0" @click="removeRole">
                        Delete</button>
                    </div>

                    <h4>Permissions</h4>
                    <!-- Permissions Table -->
                    <div class="table-responsive">
                        <table id="table-role-permissions" class="table table-standard table-bordered">
                            <tbody>
                            <template v-for="permission in permissions">
                                <tr>
                                    <td>
                                        @{{ permission.label }}
                                    </td>
                                    <td v-if="hasPermission(permission, selectedRole)"
                                        class="clickable td-has-permission permission-check"
                                    @click="removePermission(permission, selectedRole)"
                                    :class="{
                                        'admin': selectedRole.position === 'admin'
                                    }"
                                    >
                                    <i class="fa fa-check"></i>
                                    <i class="fa fa-close"></i>
                                    </td>
                                    <td v-else
                                        class="clickable td-no-permission permission-check"
                                    @click="givePermission(permission, selectedRole)"
                                    >
                                    <i class="fa fa-check"></i>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <h4>Staff</h4>
                    <ul class="list-unstyled no-margin" v-if="selectedRole.users && selectedRole.users.length > 0">
                        <li v-for="user in selectedRole.users" class="capitalize"><a :href="'/staff/' + user.id" alt="link to single staff">@{{ user.name }}</a></li>
                    </ul>
                    <em v-else>none</em>
                </div>
            </div>
        </div>
        </div>
    </settings-roles>



@endsection

