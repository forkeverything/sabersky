@extends('layouts.app')

@section('content')
    <report-spendings-items inline-template :user="user">
        <div id="report-spendings-items" class="container">
            <div class="page-body">
                <date-range-field :min.sync="dateMin" :max.sync="dateMax"></date-range-field>
                <company-currency-selecter :id.sync="currencyId"
                                           :currencies="companyCurrencies"></company-currency-selecter>
                <spendings-items-chart :chart-data="spendingsData"></spendings-items-chart>
            </div>
            @include('reports.spendings.partials.disclaimer-costs')
        </div>
    </report-spendings-items>
@endsection