@extends('layouts.app')

@section('content')
    <div class="container">
        <a href="{{ route('allProjects') }}" class="back-link no-print"><i class="fa  fa-arrow-left fa-btn"></i>Projects</a>
        <div class="page-header">
            <h1 class="page-title">List a new project</h1>
        </div>
        <div class="page-body">
            <h5>Project Details</h5>
            @include('errors.list')
            <form action="{{ route('startProject') }}" id="form-project-start" method="POST">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="field-project-name">Name</label>
                    <input type="text" id="field-project-name" name="name" value="{{ old('name') }}" class="form-control">
                </div>
                <div class="form-group">
                    <label for="field-project-location">Location</label>
                    <input type="text" id="field-project-location" name="location" value="{{ old('location') }}"
                           class="form-control">
                </div>
                <div class="form-group">
                    <label for="field-project-description">Description</label>
                <textarea name="description" id="field-project-description" rows="15" class="form-control">
                    {{ old('$NAME') }}
                </textarea>
                </div>
                <!-- Submit -->
                    <button type="submit" class="btn btn-solid-blue form-control">Start Project</button>
            </form>
        </div>
    </div>
@endsection