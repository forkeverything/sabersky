@extends('layouts.app')

@section('content')
    <div class="container" id="project-edit">
        <form action="{{ route('updateProject', $project->id) }}" method="POST">
            {{ csrf_field() }}
            @include('errors.list')
            <div class="page-body">
                <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" value="{{ $project->name }}" class="form-control" name="name">
                </div>

                <div class="form-group">
                    <label for="">Location</label>
                    <input type="text" value="{{ $project->location }}" class="form-control" name="location">
                </div>

                <div class="form-group">
                    <label for="">Description</label>
                    <textarea name="description" class="form-control autosize">{{ $project->description }}</textarea>
                </div>
            </div>
            <div class="bottom">
                <button type="submit" class="btn btn-outline-green">Update</button>
            </div>
        </form>
    </div>
@stop