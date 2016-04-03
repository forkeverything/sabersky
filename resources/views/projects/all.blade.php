@extends('layouts.app')

@section('content')
    <div class="container" id="projects-all">
        @can('project_manage')
        <div class="row">
            <div class="col-sm-4 col-sm-offset-8">
                <a href="/projects/start"><button class="btn btn-solid-green button-start-project">New Project</button></a>
            </div>
        </div>
        @endcan
        <div class="page-body">
            @if($company->projects()->first())
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
            @else
                <span class="page-error">There are currently no projects.</span>
            @endif
        </div>
    </div>
@endsection