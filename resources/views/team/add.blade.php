@extends('layouts.app')
@section('content')
    <div class="container">
        <h1>Add Team Member</h1>
        <form action="/team/add" method="POST">
            {{ csrf_field() }}
            @include('errors.list')
            @include('team.partials.new-user-fields')
            <div class="bottom align-end">
                <button type="submit" class="btn btn-solid-green">Send Invitation</button>
            </div>
        </form>
    </div>
@stop