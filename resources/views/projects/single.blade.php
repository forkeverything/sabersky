@extends('layouts.app')
@section('content')
    <project-single inline-template>
        <div class="container" id="project-single-view">
            <input id="hidden-project-id" type="hidden" value="{{ $project->id }}">
            <div class="page-body">
                <section class="project-header">
                    <h5>Status</h5>
                    @if($project->operational)
                        <span class="project-status active label label-success">Currently Developing</span>
                    @else
                        <span class="project-status inactive label label-default">Inactive</span>
                    @endif
                </section>
                <section class="project-description">
                    <h5>Description</h5>
                    <p>
                        {{ $project->description }}
                    </p>
                </section>
                <section class="team-members">
                    <div class="team-header">
                        <h5>Team</h5>
                        @can('team_manage')
                        <a href="{{ route('addTeamMember', $project->id) }}">
                            <button class="btn btn-outline-blue"><i class="fa fa-user-plus fa-btn"></i>Add Team Member
                            </button>
                        </a>
                        @endcan
                    </div>
                    <power-table :headers="tableHeaders" :data="teamMembers"></power-table>
                </section>
            </div>
        </div>
    </project-single>
@endsection