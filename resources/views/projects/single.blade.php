@extends('layouts.app')
@section('content')
    <div class="container" id="project-single-view">
        <a href="{{ route('allProjects') }}" class="link-underline">Back to Projects</a>
        <section class="project-info">
            <h1 class="page-title">{{ $project->name }}</h1>
            <div class="project-bar">
                @if($project->operational)
                    <span class="project-status active">Currently Developing</span>
                @else
                    <span class="project-status inactive">Inactive</span>
                @endif
            </div>
            <p>
                {{ $project->description }}
            </p>
        </section>
        <section class="team-members">
            <h5>Team Members</h5>
            <button class="btn btn-default"><i class="fa fa-user-plus fa-btn"></i>Add Team Member</button>
            <div class="team-wrap">
                @foreach($project->teamMembers->chunk(3) as $chunk)
                    <div class="row">
                        @foreach($chunk as $member)
                            <div class="team-single-member col-md-4">
                                <i class="fa fa-user"></i>
                            <span>
                            <strong>{{ $member->name }}</strong>
                                @if($member->id == Auth::user()->id)
                                    <br>(You)
                                @endif
                            </span>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </section>
    </div>
@endsection