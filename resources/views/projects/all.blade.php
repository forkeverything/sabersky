@extends('layouts.app')

@section('content')
    <div class="container" id="projects-all">
        @can('project_manage')
        <div class="top">
            <a class="link-new-project" href="/projects/start">
                <button class="btn btn-outline-green button-start-project">New Project</button>
            </a>
        </div>
        @endcan
        <div class="page-body">
            @if($company->projects()->first())
                <div class="project-list">
                    @foreach($company->projects as $project)
                        <div class="project-single">
                            <div class="left">
                                <div class="project-thumbnail">
                                    @if($project->thumbnail)
                                        <img src="#">
                                    @else
                                        <i class="project-placeholder fa fa-building"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="right">
                                <a href="{{ route('singleProject', $project->id) }}" class="project-single-link">
                                    <h5 class="project-name">
                                        {{ $project->name }}
                                    </h5>
                                </a>
                                <table class="project-details">
                                    <tbody>
                                    <tr>
                                        <th>Created</th>
                                        <td>{{ $project->created_at->format('M d, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Location</th>
                                        <td>{{ $project->location }}</td>
                                    </tr>
                                    <tr>
                                        <th>Team Members</th>
                                        <td>{{ count($project->teamMembers) }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <span class="page-error">There are currently no projects.</span>
            @endif
        </div>
    </div>
@endsection