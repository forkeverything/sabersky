@extends('layouts.app')

@section('content')
    <staff-single inline-template :staff="{{ $user }}">
        <div class="container" id="staff-single">
            <div class="title-with-buttons">
                <h1>{{ $user->name }}</h1>

                @if(Auth::user()->hasRole('admin') && ! $user->hasRole('admin'))
                    @include('staff.partials.single.toggle-active')
                @endif
            </div>

            <h4>Status</h4>
            <p class="staff-status {{ $user->status }}">
                {{ $user->status }}
            </p>

            <h4>Role</h4>

            <div class="role-position">
                <span>{{ $user->role->position }}</span>
                @if(! $user->role->position === 'admin' ||  Gate::allows('team_manage'))
                    <button type="button"
                            class="btn btn-outline-blue"
                            v-show="! showChangeRoleForm"
                            @click="toggleRoleForm"
                    >
                    Change
                    </button>
                @endif
            </div>

            @if(! $user->role->position === 'admin' ||  Gate::allows('team_manage'))
                @include('staff.partials.single.form-change-role')
            @endif


            <h4>Projects</h4>
            @if($user->projects->first())
                <ul class="list-unstyled project-list">
                    @foreach($user->projects as $project)
                        <li><a href="/projects/{{ $project->id }}">{{ $project->name }}</a></li>
                    @endforeach
                </ul>
            @else
                <p><em class="text-muted">None</em></p>
            @endif

            <h4>Email</h4>
            <p class="text-lowercase">{{ $user->email }}</p>

            <h4>Date Joined</h4>
            <p>
                {{ $user->created_at->format('d M Y') }}
            </p>

            <modal></modal>
        </div>
    </staff-single>
@stop