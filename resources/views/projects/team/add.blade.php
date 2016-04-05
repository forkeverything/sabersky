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
                        @include('team.partials.new-user-fields')
                    </section>
                </div>
                <div class="bottom">
                    <button type="submit" class="btn btn-solid-blue">Add Team Member
                    </button>
                </div>
            </form>
        </div>
    </projects-add-team>
@endsection