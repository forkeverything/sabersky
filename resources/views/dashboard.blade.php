@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="page-header">
                    <h1 class="page-title">{{ Auth::user()->role->position }} Dashboard</h1>
                </div>
                <div id="dashboard-nav">
                    <div class="row">
                        <div class="col-sm-4"><a class="dashboard-link" href="/projects">
                                <i class="fa fa-flash icon-dashboard"></i>Projects & Teams
                            </a></div>
                        <div class="col-sm-4"><a class="dashboard-link" href="/purchase_requests">
                                <i class="fa fa-shopping-basket icon-dashboard"></i>
                                Purchase Requests
                            </a>
                        </div>
                        <div class="col-sm-4"><a class="dashboard-link" href="/vendors">
                                <i class="fa fa-truck icon-dashboard"></i>
                                Vendors
                            </a></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4"><a class="dashboard-link" href="/items">
                                <i class="fa fa-legal icon-dashboard"></i>
                                Items
                            </a></div>
                        <div class="col-sm-4"><a class="dashboard-link" href="/purchase_orders">
                                <i class="fa fa-clipboard icon-dashboard"></i>
                                Purchase Orders
                            </a>
                        </div>
                        @can('report_view')
                        <div class="col-sm-4"><a class="dashboard-link" href="/reports">
                                <i class="fa fa-bar-chart icon-dashboard"></i>
                                Reports
                            </a></div>
                        @endcan
                    </div>
                    <div class="row">
                        @can('settings_change')
                        <div class="col-sm-4"><a class="dashboard-link" href="/settings">
                                <i class="fa fa-gears icon-dashboard"></i>
                                Settings
                            </a></div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
