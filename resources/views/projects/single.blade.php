@extends('layouts.app')
@section('content')
    <project-single inline-template :project="{{ $project }}">
        <div class="container" id="project-single-view">
            <h1>{{ $project->name }}</h1>

            <div class="project-header part">
                <h4>Status</h4>
                @if($project->operational)
                    <h2 class="project-status active">Active</h2>
                @else
                    <h2 class="project-status inactive">Inactive</h2>
                @endif
            </div>
            <div class="project-description part">
                <h4>Description</h4>
                <p>
                    {{ $project->description }}
                </p>
            </div>
            <div class="team-members part">
                <div class="team-header">
                    <h4>Team</h4>
                    @can('team_manage')
                        <a href="{{ route('addTeamMember', $project->id) }}">
                            <button class="btn btn-outline-blue"><i class="fa fa-user-plus fa-btn"></i>Add Team Member
                            </button>
                        </a>
                    @endcan
                </div>
                <div class="table-team" v-if="project.team_members.length > 0">
                    <power-table :headers="tableHeaders" :data="project.team_members" :sort="true"
                                 :hover="true"></power-table>
                </div>
                <p class="text-muted" v-else><em>No team members currently working on this Project</em></p>
            </div>
            @include('layouts.partials.activities_log', ['activities' => $project->activities])

        </div>
    </project-single>
@endsection