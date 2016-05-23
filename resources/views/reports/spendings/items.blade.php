@extends('layouts.app')

@section('content')
    <report-spendings-items inline-template :user="user">
        <div id="report-spendings-items" class="container spendings-report">
            <div class="page-body">
                @include('reports.spendings.partials.controls')
                @include('reports.spendings.partials.title')
                <spendings-items-chart :chart-data="spendingsData"></spendings-items-chart>
            </div>
            @include('reports.spendings.partials.disclaimer-costs')
        </div>
    </report-spendings-items>
@endsection