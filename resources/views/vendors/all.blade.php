@extends('layouts.app')
@section('content')
    <div class="container" id="vendors-all">
        <div class="title-with-buttons">
            <h1>Vendors</h1>

            <div class="buttons">
                @can('vendor_manage')
                    <a href="{{ route('addVendor') }}">
                        <button type="button" class="btn-add-vendor btn btn-solid-green">Add Vendor</button>
                    </a>
                @endcan
            </div>
        </div>
        @if($vendors->first())
            <div class="table-responsive ">
                <!-- Vendors Table Table -->
                <table class="table table-hover table-standard">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($vendors as $vendor)
                        <tr>
                            <td><a href="/vendors/{{ $vendor->id }}" alt="Single vendor link">{{ $vendor->name }}</a>
                            </td>
                            <td>
                                @if($vendor->description)
                                    {{ str_limit($vendor->description, 150) }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div v-else class="empty-stage">
                <i class="fa fa-building"></i>
                <h4>No Vendors Found</h4>
                <p>Add a vendor first before you can submit purchase orders to them</p>
            </div>
        @endif
    </div>
@endsection