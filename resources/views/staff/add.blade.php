@extends('layouts.app')
@section('content')
    <div class="container">
        <h1>Add Staff Member</h1>
        <form action="/staff/add" method="POST">
            {{ csrf_field() }}
            @include('errors.list')
            <div class="form-group">
                <label for="field-new-user-name">Name</label>
                <input type="text"
                       id="field-new-user-name"
                       class="form-control"
                       name="name"
                >
            </div>
            <div class="form-group">
                <label for="field-new-user-email">Email</label>
                <input type="email"
                       id="field-new-user-email"
                       class="form-control"
                       name="email"
                >
            </div>
            <div class="form-group">
                <label for="field-new-user-role">Staff Role</label>
                <select id="field-new-user-role" v-selectize name="role_id">
                    <option disabled selected value="">Choose a position</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ ucwords($role->position) }}</option>
                    @endforeach
                </select>
    <span class="small">
        You can add more Roles from the <a href="/settings/permissions">Settings Page</a>
    </span>
            </div>

            <div class="bottom align-end">
                <button type="submit" class="btn btn-solid-green">Send Invitation</button>
            </div>
        </form>
    </div>
@stop