<div class="form-group">
    <label for="field-new-user-name">Name</label>
    <input type="text"
           id="field-new-user-name"
           class="form-control"
           v-model="newUserName"
    >
</div>
<div class="form-group">
    <label for="field-new-user-email">Email</label>
    <input type="email"
           id="field-new-user-email"
           class="form-control"
           v-model="newUserEmail"
    >
</div>
<div class="form-group">
    <label for="field-new-user-role">Staff Role <span class="small">(You can add more Roles from the <a href="/settings">Settings Page</a>)</span></label>
    <select id="field-new-user-role" class="form-control" v-selectize="newUserRoleId">
        <option disabled selected value="">Choose a position</option>
        @foreach($roles as $role)
            <option value="{{ $role->id }}">{{ ucwords($role->position) }}</option>
        @endforeach
    </select>
</div>
