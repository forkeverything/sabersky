@extends('layouts.app')
@section('content')
    <div class="container" id="project-single-view">
        <a href="{{ route('allProjects') }}" class="back-link"><i class="fa  fa-arrow-left fa-btn"></i>Back to Projects</a>
       <div class="page-header">
           <h1 class="page-title">{{ $project->name }}</h1>
               @if($project->operational)
                   <span class="project-status active label label-success">Currently Developing</span>
               @else
                   <span class="project-status inactive label label-default">Inactive</span>
               @endif
       </div>
        <p>
            {{ $project->description }}
        </p>
        <section class="team-members">
            <h5>Team Members</h5>
            @if(Auth::user()->is('director') || Auth::user()->is('manager'))
            <a href="{{ route('addTeamMember', $project->id) }}"><button class="btn btn-default"><i class="fa fa-user-plus fa-btn"></i>Add Team Member</button></a>
            @endif
            <div class="team-wrap">
                @foreach($project->teamMembers->chunk(3) as $chunk)
                    <div class="row">
                        @foreach($chunk as $member)
                            <div class="team-single-member col-md-4">
                                @if($member->invite_key)
                                    <i class="fa fa-user pending"></i>
                                    @else
                                <i class="fa fa-user"></i>
                                @endif
                            <span>
                            <strong>{{ $member->name }}</strong>
                                <br>
                                {{ ucfirst($member->role->position) }}
                                @if($member->id == Auth::user()->id)
                                    <em>(You)</em>
                                @elseif($member->invite_key)
                                    <em>(Pending)</em>
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