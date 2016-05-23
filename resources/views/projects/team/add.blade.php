@extends('layouts.app')
@section('content')
    <projects-add-team inline-template :project="{{ $project }}">
        <div class="container" id="projects-team-add">
            <form id="form-add-user"
                  @submit.prevent="addTeamMember"
            >
                <div class="page-body">
                    <form-errors></form-errors>
                    <section class="add-existing-user">
                        <h5>Existing User</h5>
                        <div class="form-group">
                            <company-employee-search-selecter :name.sync="existingUserId"></company-employee-search-selecter>
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