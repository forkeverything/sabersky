@extends('layouts.app')
@section('content')
    <div class="container" id="projects-team-add">
        <a href="{{ route('singleProject', $project->id) }}" class="link-underline"><i
                    class="fa  fa-arrow-left fa-btn"></i>Back to {{ $project->name }}</a>
        <div class="panel panel-default">
            <div class="panel-heading text-center"><strong>Add Team Member for {{ $project->name }}</strong></div>
            <div class="panel-body">
                @include('errors.list')
                <form action="{{ route('saveTeamMember', $project->id) }}" id="form-add-user"
                      method="POST">
                    {{ csrf_field() }}
                    <section class="add-existing-user">
                        <h4>Existing User</h4>
                        <select name="existing_user" id="field-existing-user">
                            <option disabled value="" selected>Please select a user</option>
                            @foreach($project->company->employees as $employee)
                                @if(! $project->teamMembers->contains($employee))
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </section>
                    <section class="add-new-user">
                        <h4>New User</h4>
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
                    </section>
                    <!-- Submit -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary form-control">Add Team Member</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection