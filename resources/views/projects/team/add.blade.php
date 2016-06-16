@extends('layouts.app')
@section('content')
    <projects-add-team inline-template :project="{{ $project }}">
        <div class="container" id="projects-team-add">
            <h1>Add Team Member to {{ $project->name }}</h1>
            <form id="form-add-user"
                  @submit.prevent="addTeamMember"
            >
                    <form-errors></form-errors>
                        <h2>Search Company Staff</h2>
                        <div class="form-group">
                            <company-employee-search-selecter :name.sync="existingUserId"></company-employee-search-selecter>
                        </div>
                <div class="bottom">
                    <button type="submit" class="btn btn-solid-blue">Add Team Member
                    </button>
                </div>
            </form>
        </div>
    </projects-add-team>
@endsection