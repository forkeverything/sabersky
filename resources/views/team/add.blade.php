@extends('layouts.app')
@section('content')
    <div class="container">
        <form action="/team/add" method="POST">
            {{ csrf_field() }}
            @include('errors.list')
            <div class="page-body">
                @include('team.partials.new-user-fields')
            </div>
            <div class="bottom align-end">
                <button type="submit" class="btn btn-solid-green">Send Invitation</button>
            </div>
        </form>
    </div>
@stop