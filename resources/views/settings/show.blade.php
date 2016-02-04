@extends('layouts.app')
@section('content')
    <div class="container" id="system-settings">
        <a href="{{ route('dashboard') }}" class="back-link no-print"><i class="fa  fa-arrow-left fa-btn"></i>Dashboard</a>
        <div class="page-header">
            <h1 class="page-title">
                System Settings
            </h1>
        </div>
        <p>Change Application settings to determine what needs approval for whom. Defaults have been automatically set for you.</p>
        @include('errors.list')
        <form action="{{ route('saveSettings') }}" id="form-settings" method="POST">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="field-po-high-max">
                    <strong>High PO Threshold</strong>
                    <br>
                    Purchase orders with totals over this amount will require <em>Director's</em> approval
                </label>
                <input type="number" id="field-po-high-max" name="po_high_max" value="{{ $settings->po_high_max }}" class="form-control">
            </div>
            <div class="form-group">
                <label for="field-po-med-max">
                    <strong>Medium PO Threshold</strong>
                    <br>
                    Purchase orders with totals over this amount will require <em>Manager's</em> approval
                </label>
                <input type="number" id="field-po-med-max" name="po_med_max" value="{{ $settings->po_med_max }}" class="form-control">
            </div>
            <div class="form-group">
                <label for="field-item-md-max">
                    <strong>Maximum Item Mean Difference</strong>
                    <br>
                    Items with a mean difference percentage over this amount will require <em>Manager's</em> approval
                </label>
                <input type="number" step="0.01" id="field-item-md-max" name="item_md_max" value="{{ $settings->item_md_max }}" class="form-control">
            </div>
            <!-- Submit -->
            <div class="form-group">
                <button type="submit" class="btn btn-solid-blue form-control">Save Settings</button>
            </div>
        </form>
    </div>
@endsection