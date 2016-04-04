@extends('layouts.app')
@section('content')
    <projects-add-team inline-template>
        <div class="container" id="projects-team-add">
            <form action="{{ route('saveTeamMember', $project->id) }}" id="form-add-user"
                  method="POST">
            <div class="page-body">
                @include('errors.list')
                    {{ csrf_field() }}
                    <section class="add-existing-user">
                        <h5>Existing User</h5>
                        <div class="form-group">
                            <select name="existing_user_id" id="field-existing-user" class="form-control">
                                <option disabled value="" selected>Please select a user</option>
                                @foreach($project->company->employees as $employee)
                                    @if(! $project->teamMembers->contains($employee))
                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </section>
                    <section class="add-new-user">
                        <h5>New User</h5>
                        <div class="form-group">
                            <label for="field-new-user-name">Name</label>
                            <input type="text" id="field-new-user-name" name="name" value="{{ old('name') }}"
                                   class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="field-new-user-email">Email</label>
                            <input type="text" id="field-new-user-email" name="email" value="{{ old('email') }}"
                                   class="form-control">
                        </div>
                        <label for="field-new-user-role">Role</label>
                        <select name="role_id" id="field-new-user-role" class="form-control" v-selectize>
                            <option disabled selected value="">Choose a position</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ ucwords($role->position) }}</option>
                            @endforeach
                        </select>
                    </section>
            </div>
            <!-- Submit -->
            <div class="row">
                <div class="col-md-3 col-md-offset-9">
                    <div class="form-group">
                        <button type="submit" class="btn btn-solid-green form-control">Add Team Member
                        </button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </projects-add-team>
@endsection