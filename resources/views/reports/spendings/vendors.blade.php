@extends('layouts.app')

@section('content')
    <report-spendings-vendors inline-template :user="user">
        <div id="report-spendings-vendors" class="container spendings-report">
            <div class="page-body">
                @include('reports.spendings.partials.controls')
                @include('reports.spendings.partials.title')
                <spendings-vendors-chart :chart-data="spendingsData"></spendings-vendors-chart>
            </div>
            @include('reports.spendings.partials.disclaimer-costs')
        </div>
    </report-spendings-vendors>
@endsection