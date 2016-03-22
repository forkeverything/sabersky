@extends('layouts.app')

@section('content')
    <div class="container" id="projects-all">
        <a href="{{ route('dashboard') }}" class="back-link no-print"><i class="fa  fa-arrow-left fa-btn"></i>Dashboard</a>
        <div class="page-header">
            <h1 class="page-title">Projects & Teams</h1>
            <p class="page-intro">List of <span class="capitalize">{{ $company->name }}</span> Projects. Select or create a new project and begin sending invitations to team members.</p>
        </div>
        <div class="page-body">
            @can('project_manage')
            <a href="/projects/start"><button class="btn btn-solid-blue button-start-project">Start New Project</button></a>
            @endcan
            <div class="project-list">
                @foreach($company->projects as $project)
                    <a href="{{ route('singleProject', $project->id) }}" class="project-single-link">
                        <div class="project-single">
                            <h3>
                                {{ $project->name }}
                            </h3>
                            <p class="text-muted">
                                {{ $project->location }}
                            </p>
                            <p>
                                {{ str_limit($project->description, 300, '...') }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>

            @if(! $company->projects()->first())
                <span class="page-error">There are currently no projects.</span>
            @endif
        </div>
    </div>
@endsection