@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="page-title">Start a new project</h2>
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
            <div class="form-group">
                <button type="submit" class="btn btn-primary form-control">Start Project</button>
            </div>
        </form>


    </div>
@endsection