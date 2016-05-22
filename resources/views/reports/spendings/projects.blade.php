@extends('layouts.app')

@section('content')
    <report-spendings-projects inline-template :user="user">
        <div id="report-spendings-projects" class="container">
            <div class="page-body">
                <date-range-field :min.sync="dateMin" :max.sync="dateMax"></date-range-field>
                <company-currency-selecter :id.sync="currencyId" :currencies="companyCurrencies"></company-currency-selecter>
                <div style="width: 400px">
                <spendings-projects-chart :currency-id="currencyId" :date-min="dateMin" :date-max="dateMax"></spendings-projects-chart>
                </div>
            </div>
            @include('reports.spendings.partials.disclaimer-costs')
        </div>
    </report-spendings-projects>
@endsection