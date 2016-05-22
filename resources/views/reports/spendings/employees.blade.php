@extends('layouts.app')

@section('content')
    <report-spendings-employees inline-template :user="user">
        <div id="report-spendings-employees" class="container">
            <div class="page-body">
                <date-range-field :min.sync="dateMin" :max.sync="dateMax"></date-range-field>
                <company-currency-selecter :id.sync="currencyId" :currencies="companyCurrencies"></company-currency-selecter>
                <spendings-employees-chart :chart-data="spendingsData"></spendings-employees-chart>
            </div>
            @include('reports.spendings.partials.disclaimer-costs')
        </div>
    </report-spendings-employees>
@endsection