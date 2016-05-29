@extends('layouts.app')
@section('content')
    <projects-add-team inline-template :project="{{ $project }}">
        <div class="container" id="projects-team-add">
            <h1>Add Team Member to {{ $project->name }}</h1>
            <form id="form-add-user"
                  @submit.prevent="addTeamMember"
            >

                    <form-errors></form-errors>
                    <section class="add-existing-user">
                        <h2>Existing User</h2>
                        <div class="form-group">
                            <company-employee-search-selecter :name.sync="existingUserId"></company-employee-search-selecter>
                        </div>
                    </section>
                    <section class="add-new-user">
                        <h2>New User</h2>
                        @include('team.partials.new-user-fields')
                    </section>
                <div class="bottom">
                    <button type="submit" class="btn btn-solid-blue">Add Team Member
                    </button>
                </div>
            </form>
        </div>
    </projects-add-team>
@endsection