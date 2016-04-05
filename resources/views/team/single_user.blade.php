@extends('layouts.app')

@section('content')
    <team-single-user inline-template>
        <div class="container" id="team-single-user">
            <section class="top children-right">
                <button class="btn btn-solid-red no-outline button-delete-user"
                        type="button"
                @click="confirmDelete({{ $user }})"
                ><i class="fa fa-trash"></i>Permanently Delete User</button>
            </section>
            <div class="page-body">
                <h5 class="capitalize">{{ $user->name }}</h5>
                <div class="table-responsive table-user-details">
                    <!-- User Profile Table -->
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($user->isPending())
                                    <span class="badge badge-warning">Pending</span>
                                @else
                                    <span class="badge badge-succes">Confirmed</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Joined</th>
                            <td>
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                        </tr>
                        <tr>
                            <th>Role</th>
                            <td>
                                @if($user->role->position === 'admin' || ! Gate::allows('team_manage'))
                                    {{ ucwords($user->role->position) }}
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
                            </td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>
                                {{ $user->email }}
                            </td>
                        </tr>
                        <tr>
                            <th>Projects</th>
                            <td>
                                @if($user->projects->first())
                                    <ul class="list-unstyled project-list">
                                        @foreach($user->projects as $project)
                                            <li><a class="button-dotted"
                                                   href="/projects/{{ $project->id }}">{{ $project->name }}</a></li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="badge badge-warning">None Assigned</span>
                                @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <modal></modal>
        </div>
    </team-single-user>
@stop