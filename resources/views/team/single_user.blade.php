@extends('layouts.app')

@section('content')
    <team-single-user inline-template>
        <div class="container" id="team-single-user">
            <div class="title-with-buttons">
                <h1>{{ $user->name }}</h1>
                <div class="buttons">
                    <button class="btn btn-outline-red  button-delete-user"
                            type="button"
                    @click="confirmDelete({{ $user }})"
                    ><i class="fa fa-trash"></i>Delete</button>
                </div>
            </div>

            <h4>Status</h4>
            @if($user->isPending())
                <p class="badge badge-warning">Pending</p>
            @else
                <p class="badge badge-success">Confirmed</p>
            @endif

            <h4>Role</h4>

            @if($user->role->position === 'admin' || ! Gate::allows('team_manage'))
                <p>{{ ucwords($user->role->position) }}</p>
            @else
                <form class="form-change-role " action="/team/user/{{ $user->id }}/role"
                      method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="PUT">
                    <select v-selectpicker @change="showChangeButton" name="role_id">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}"
                                    @if($role->position === $user->role->position) selected @endif>{{ $role->position }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-solid-green button-change-role"
                            v-show="changeButton">Change
                    </button>
                </form>
                @endif

            <h4>Projects</h4>
            @if($user->projects->first())
                <ul class="list-unstyled project-list">
                    @foreach($user->projects as $project)
                        <li><a href="/projects/{{ $project->id }}">{{ $project->name }}</a></li>
                    @endforeach
                </ul>
            @else
                <p class="badge badge-warning">None Assigned</p>
            @endif

            <h4>Email</h4>
            <p>{{ $user->email }}</p>

            <h4>Date Joined</h4>
            <p>
                {{ $user->created_at->format('d M Y') }}
            </p>

            <modal></modal>
        </div>
    </team-single-user>
@stop