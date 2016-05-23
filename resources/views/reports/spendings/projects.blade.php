@extends('layouts.app')

@section('content')
    <report-spendings-projects inline-template :user="user">
        <div id="report-spendings-projects" class="container spendings-report">
            <div class="page-body">
                @include('reports.spendings.partials.controls')
                @include('reports.spendings.partials.title')
                <spendings-projects-chart :chart-data="spendingsData"></spendings-projects-chart>
            </div>
            @include('reports.spendings.partials.disclaimer-costs')
        </div>
    </report-spendings-projects>
@endsection