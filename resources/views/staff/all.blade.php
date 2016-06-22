@extends('layouts.app')
@section('content')
    <staff-all inline-template :user="user">
        <div class="container" id="staff-all">
            <div class="title-with-buttons">
                <h1 class="capitalize">@{{ user.company.name }} Staff</h1>
                @can('team_manage')
                    <div class="buttons">
                        <a href="/staff/add" class="link-add-team button-right">
                            <button class="btn btn-solid-green"><i class="fa fa-user-plus fa-btn"></i>Invite Staff
                            </button>
                        </a>
                    </div>
                @endcan
            </div>
            <div class="table-staff">
            {{--<power-table :headers="tableHeaders" :data="employees" :sort="true" :hover="true"></power-table>--}}


            <!-- Staff Table -->
                <table class="table table-hover table-standard">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Status</th>
                    <tr>
                    </thead>
                    <tbody>
                    <template v-for="staff in staff">
                        <tr>
                            <td class="capitalize">
                                <a :href="'/staff/' + staff.id">@{{{ staff.name }}}</a>
                            </td>
                            <td class="capitalize">
                                @{{ staff.role.position }}
                            </td>
                            <td>
                                @{{ staff.email }}
                            </td>
                            <td :class="{
                                    'success': staff.status === 'active',
                                    'warning': staff.status === 'pending'
                                }"
                            >
                                @{{ staff.status }}
                            </td>
                        </tr>
                    </template>
                    </tbody>
                </table>
            </div>
        </div>
    </staff-all>
@stop