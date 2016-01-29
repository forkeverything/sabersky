@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ ucfirst(Auth::user()->role->position) }} Dashboard</div>

                    <div class="panel-body">
                        <div id="dashboard-nav">
                            <div class="nav-item">Projects</div>
                            <div class="nav-item">Purchase Requests</div>
                            <div class="nav-item">Vendors</div>
                            <div class="nav-item">Items</div>
                            <div class="nav-item">Purchase Orders</div>
                            @can('report_view')
                            <div class="nav-item">Reports</div>
                            @endcan
                            @can('settings_view')
                            <div class="nav-item">Settings</div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
