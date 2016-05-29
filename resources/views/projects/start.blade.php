@extends('layouts.app')

@section('content')
    <div id="project-start">
        <div class="container">
            <h1>Begin a new project</h1>
            @include('errors.list')
            <form action="{{ route('startProject') }}" id="form-project-start" method="POST">

                {{ csrf_field() }}
                <div class="form-group">
                    <label for="field-project-name">Project Name</label>
                    <input type="text" id="field-project-name" name="name" value="{{ old('name') }}"
                           class="form-control">
                </div>
                <div class="form-group">
                    <label for="field-project-location">Address / Location</label>
                    <input type="text" id="field-project-location" name="location" value="{{ old('location') }}"
                           class="form-control">
                </div>
                <div class="form-group">
                    <label for="field-project-description">Description</label>
                    <textarea name="description" id="field-project-description" rows="15"
                              class="form-control">{{ old('$NAME') }}</textarea>
                </div>

                <div class="bottom">
                    <button type="submit" class="btn btn-solid-green">Save New Project</button>
                </div>
            </form>
        </div>
    </div>
@endsection