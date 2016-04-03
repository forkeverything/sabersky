@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-body">
            @include('errors.list')
            <form action="{{ route('startProject') }}" id="form-project-start" method="POST">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="field-project-name">Project or Location Name</label>
                    <input type="text" id="field-project-name" name="name" value="{{ old('name') }}" class="form-control">
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
                <div class="row">
                    <div class="col-sm-4 col-sm-offset-8">
                        <button type="submit" class="btn btn-solid-blue form-control">Save New Project</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection