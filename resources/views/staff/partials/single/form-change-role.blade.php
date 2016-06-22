<form class="form-change-role part"
      action="/staff/{{ $user->id }}/role"
      method="POST"
      v-show="showChangeRoleForm"
>
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="PUT">
    <div class="form-group">
        <label>New Role</label>
        <select v-selectize="newRoleId" name="role_id">
            <option></option>
            @foreach($roles as $role)
                <option value="{{ $role->id }}"
                        @if($role->position === $user->role->position) selected @endif>{{ $role->position }}</option>
            @endforeach
        </select>
    </div>
    <button  type="button" class="btn btn-outline-grey btn-cancel" v-show="showChangeRoleForm" @click="toggleRoleForm">Cancel</button>
    <button type="submit"
            class="btn btn-solid-blue button-change-role"
            :disabled="! newRoleId"
    >
        Save
    </button>
</form>