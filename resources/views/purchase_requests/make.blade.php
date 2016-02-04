@extends('layouts.app')
@section('content')
    <div class="container" id="purchase-requests-add">
        <a href="{{ route('showAllPurchaseRequests') }}" class="back-link no-print"><i class="fa  fa-arrow-left fa-btn"></i>Back
            to
            Purchase Requests</a>
        <div class="page-header">
            <h1 class="page-title">Make Purchase Request</h1>
        </div>
        @include('errors.list')
        <form action="{{ route('savePurchaseRequest') }}" id="form-make-purchase-request" method="POST">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="field-project-id">Which Project is this Purchase Request for?</label>
                <select name="project_id" id="field-project-id" class="form-control">
                    <option disabled selected>Select a project</option>
                    @foreach(Auth::user()->projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="field-item-id">Made request for this item before?</label>
                <select name="item_id" id="field-item-id" class="form-control">
                    <option disabled selected>Choose an item</option>
                    @foreach(Auth::user()->company->items as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="field-new-item-name">New Item: Name</label>
                <input type="text" id="field-new-item-name" name="name" value="{{ old('name') }}" class="form-control" placeholder="Steel Pipe">
            </div>
            <div class="form-group">
                <label for="field-new-item-specification">New Item: Detailed Specification</label>
                <textarea name="specification" id="field-new-item-specification" rows="10" class="form-control" placeholder="60cm Diameter, 2.4 inches Thick, Length 3m...">{{ old('specification') }}</textarea>
            </div>
            <div class="form-group">
                <label for="field-quantity">How many Item(s) are needed</label>
                <input type="text" id="field-quantity" name="quantity" value="{{ old('quantity') }}" class="form-control" placeholder="10">
            </div>
            <div class="form-group">
                <label for="field-date">Approximate date required by</label>
                <input type="date" class="form-control" name="due" id="field-date">
            </div>
            <div class="form-group">
                <label for="button-urgent">Do you need this item immediately?</label>
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-default urgent-button">
                        <input type="checkbox" autocomplete="off" name="urgent" id="button-urgent" value="1">URGENT
                    </label>
                </div>
            </div>
            <!-- Submit -->
            <div class="form-group">
                <button type="submit" class="btn btn-primary form-control">Make Request</button>
            </div>
        </form>
    </div>
@endsection
