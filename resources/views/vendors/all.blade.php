@extends('layouts.app')
@section('content')
    <div class="container" id="vendors-all">
        @can('vendor_manage')
        <section class="top align-end">
            <add-vendor-modal></add-vendor-modal>
            <a href="{{ route('addVendor') }}">
                <button type="button" class="btn-add-vendor btn btn-solid-green">Add Vendor</button>
            </a>
        </section>
        @endcan
        @if($vendors->first())
            <div class="page-body">
                <div class="table-responsive">
                    <!-- Vendors Table Table -->
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Connection</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($vendors as $vendor)
                            <tr>
                                <td><a href="/vendors/{{ $vendor->id }}" alt="Single vendor link">{{ $vendor->name }}</a></td>
                                <td>
                                    @if($vendor->linkedCompany)
                                    <span class="vendor-connection {{ $vendor->linkedCompany->connection }}">{{ $vendor->linkedCompany->connection }}</span>
                                        @else
                                        <span class="vendor-connection custom">custom</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        @else
            <span class="page-error">No vendors have been registered for your company. Create a purchase order to register vendors.</span>
        @endif
    </div>
@endsection