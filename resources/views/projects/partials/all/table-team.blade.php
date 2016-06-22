<table class="table table-hover table-standard">
    <thead>
    <tr>
        <th>Name</th>
        <th>Role</th>
        <th>Email</th>
        <th>Status</th>
        @can('team_manage')
            <th></th>
        @endcan
    <tr>
    </thead>
    <tbody>
    <template v-for="staff in project.team_members">
        <tr>
            <td class="capitalize">
                <a :href="'/staff/' + staff.id">@{{{ staff.name }}}</a>
            </td>
            <td>
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
            @can('team_manage')
                <td>
                        <button type="button" class="btn close" @click="removeStaff(staff)"><i class="fa fa-close"></i></button>
                </td>
            @endcan
        </tr>
    </template>
    </tbody>
</table>