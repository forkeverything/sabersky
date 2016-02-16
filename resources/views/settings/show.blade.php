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
            <form id="form-settings">
                <div class="form-group">
                    <label for="field-po-high-max">
                        <strong>High PO Threshold</strong>
                        <br>
                        Purchase orders with totals over this amount will require <em>Director's</em> approval
                    </label>
                    <div class="input-group col-xs-2">
                        <input type="text" id="field-po-high-max" name="po_high_max" v-model="settings.po_high_max | numberModel" class="form-control text-right">
                        <span class="input-group-addon">Rp</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="field-po-med-max">
                        <strong>Medium PO Threshold</strong>
                        <br>
                        Purchase orders with totals over this amount will require <em>Manager's</em> approval
                    </label>
                    <div class="input-group col-xs-2">
                        <input type="text" id="field-po-med-max" name="po_med_max" v-model="settings.po_med_max | numberModel" class="form-control text-right">
                        <span class="input-group-addon">Rp</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="field-item-md-max">
                        <strong>Maximum Item Mean Difference</strong>
                        <br>
                        Items with a mean difference percentage over this amount will require <em>Manager's</em> approval
                    </label>
                    <div class="input-group col-xs-2">
                        <input type="number" step="1" id="field-item-md-max" name="item_md_max" v-model="settings.item_md_max | percentage" class="form-control text-right">
                        <span class="input-group-addon">%</span>
                    </div>
                </div>
                <!-- Submit -->
                <div class="form-group">
                    <button type="button" class="btn btn-solid-blue form-control" @click="saveSettings">@{{ saveButtonText }}</button>
                </div>
            </form>
    </div>
@endsection
@section('scripts.footer')
<script src="{{ asset('/js/page/settings/show.js') }}"></script>
@endsection