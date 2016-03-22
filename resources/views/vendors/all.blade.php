@extends('layouts.app')
@section('content')
    <div class="container" id="vendors-all">
        <div class="page-header">
            <h1 class="page-title">Vendors</h1>
            <p class="page-intro">Overview of all vendors, past purchases from the vendors and other relevant
                statistics.</p>
        </div>
        @if($vendors->first())
            <div class="page-body">
                <div class="table-responsive">
                    <!-- Vendors Table Table -->
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Address</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($vendors as $vendor)
                            <tr class="clickable" data-toggle="modal" data-target="#vendor{{ $vendor->id }}">
                                <td>{{ $vendor->name }}</td>
                                <td>{{ $vendor->phone }}</td>
                                <td>{{ $vendor->address }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                @foreach($vendors as $vendor)
                        <!-- Modal -->
                <div class="modal fade" id="vendor{{ $vendor->id }}" tabindex="-1" role="dialog"
                     aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                                <h3 class="modal-title" id="myModalLabel">{{ $vendor->name }}</h3>
                            </div>
                            <div class="modal-body">
                                <h2>Contact Details</h2>
                                <p class="contact-details">
                                    <strong>Phone: </strong>{{ $vendor->phone }}
                                    <br>
                                    <strong>Address: </strong>{{ $vendor->address }}
                                </p>
                                <div class="vendor_stats">
                                    <h2>Overview</h2>
                                    <div class="table-responsive">
                                        <!-- Vendor Stat Table Table -->
                                        <table class="table table-bordered">
                                            <tbody>
                                            <tr>
                                                <th>Number of Purchase Orders</th>
                                                <td>{{ $vendor->numberPO }}</td>
                                            </tr>
                                            <tr>
                                                <th>Average PO Amount</th>
                                                <td>{{ number_format($vendor->averagePO, 2) }} Rp</td>
                                            </tr>
                                            <tr>
                                                <th>Contacted by</th>
                                                <td>{{ $vendor->contactedBy }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="vendor-past-pos table-responsive">
                                    <h2>Past Purchase Orders</h2>
                                    @if($vendor->purchaseOrders()->first())
                                            <!--  Table -->
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>Project</th>
                                            <th>Submitted On</th>
                                            <th>Total</th>
                                            @can('report_view')
                                            <th>New Item</th>
                                            <th>Over High</th>
                                            <th>Over Med</th>
                                        @endcan
                                        <tr>
                                        </thead>
                                        <tbody>
                                        @foreach($vendor->purchaseOrders as $purchaseOrder)
                                            <tr>
                                                <td class="capitalize">{{ $purchaseOrder->project->name }}</td>
                                                <td>{{ $purchaseOrder->created_at->format ('d M Y') }}</td>
                                                <td>{{ $purchaseOrder->total }}</td>
                                                @can('report_view')
                                                <td class="icon">
                                                    @if($purchaseOrder->new_item)
                                                        <i class="fa fa-check"></i>
                                                    @else
                                                        <i class="fa fa-close"></i>
                                                    @endif
                                                </td>
                                                <td class="icon">
                                                    @if($purchaseOrder->over_high)
                                                        <i class="fa fa-check"></i>
                                                    @else
                                                        <i class="fa fa-close"></i>
                                                    @endif
                                                </td>
                                                <td class="icon">
                                                    @if($purchaseOrder->over_med)
                                                        <i class="fa fa-check"></i>
                                                    @else
                                                        <i class="fa fa-close"></i>
                                                    @endif
                                                </td>
                                                @endcan
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    @else
                                        <p class="text-center">No P/O's have been submitted for this vendor</p>
                                    @endif
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                {{--<button type="button" class="btn btn-primary">Save changes</button>--}}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <span class="page-error">No vendors have been registered for your company. Create a purchase order to register vendors.</span>
        @endif
    </div>
@endsection