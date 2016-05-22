@extends('layouts.app')

@section('content')
    <report-spendings-vendors inline-template :user="user">
        <div id="report-spendings-vendors" class="container">
            <div class="page-body">
                <date-range-field :min.sync="dateMin" :max.sync="dateMax"></date-range-field>
                <company-currency-selecter :id.sync="currencyId" :currencies="companyCurrencies"></company-currency-selecter>
                <spendings-vendors-chart :chart-data="spendingsData"></spendings-vendors-chart>
            </div>
            @include('reports.spendings.partials.disclaimer-costs')
        </div>
    </report-spendings-vendors>
@endsection