@extends('layouts.app')

@section('content')
    <report-spendings-employees inline-template :user="user">
        <div id="report-spendings-employees" class="container spendings-report">
            <div class="page-body">
                @include('reports.spendings.partials.controls')
                @include('reports.spendings.partials.title')
                <spendings-employees-chart :chart-data="spendingsData"></spendings-employees-chart>
            </div>
            @include('reports.spendings.partials.disclaimer-costs')
        </div>
    </report-spendings-employees>
@endsection