@extends('layouts.app')

@section('content')
    <div id="project-start">
        <div class="container">
            @include('errors.list')
            <form action="{{ route('startProject') }}" id="form-project-start" method="POST">
                <div class="page-body">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="field-project-name">Project or Location Name</label>
                        <input type="text" id="field-project-name" name="name" value="{{ old('name') }}"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="field-project-location">Address</label>
                        <input type="text" id="field-project-location" name="location" value="{{ old('location') }}"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="field-project-description">Description</label>
                <textarea name="description" id="field-project-description" rows="15" class="form-control">
                    {{ old('$NAME') }}
                </textarea>
                    </div>
                </div>
                <div class="bottom">
                    <button type="submit" class="btn btn-solid-green">Save New Project</button>
                </div>
            </form>
        </div>
    </div>
@endsection