@extends('layouts.app')

@section('content')
    <div class="container" id="projects-all">
        <a href="{{ route('dashboard') }}" class="link-underline"><i class="fa  fa-arrow-left fa-btn"></i>Back to Dashboard</a>
        <div class="page-header">
            <h1 class="page-title">Projects & Teams</h1>
        </div>
        @can('project_manage')
        <a href="/projects/start"><button class="btn btn-primary button-start-project">Start New Project</button></a>
        @endcan
        <p>List of all projects {{ $company->name }} is currently developing.</p>

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
            <h4 class="text-center">There are currently no projects.</h4>
        @endif
    </div>
@endsection