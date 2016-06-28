@extends('layouts.app')

@section('content')
    <div id="vendors-add-new" class="container">
        <h1>Add Vendor</h1>
        <p>
            You'll be able to submit a purchase order to a specific vendor as soon as you add it. You can add extra details later - for now, we'll just need a name to identify the vendor.
        </p>
        @include('errors.list')
        <form action="/vendors/add" method="POST">
            {{ csrf_field() }}
            <div class="form-group">
                <label class="required">Name</label>
                <input type="text" class="form-control" name="name">
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea class="autosize form-control" name="description"></textarea>
            </div>

            <div class="form-group align-end">
                <button type="submit" class="btn btn-solid-blue">Add Vendor</button>
            </div>
        </form>

    </div>
@endsection
