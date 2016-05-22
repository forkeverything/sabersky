@extends('layouts.app')

@section('content')
    <report-spendings inline-template :user="user">
        <div id="report-spendings" class="container">
            <div class="page-body">
                <date-range-field :min.sync="dateMin" :max.sync="dateMax"></date-range-field>
                <ul class="list-categories list-unstyled">
                    <li class=clickable" v-for="category in categories" @click="changeCategory(category)">@{{ category }}</li>
                </ul>
                <company-currency-selecter :id.sync="currencyID" :currencies="companyCurrencies"></company-currency-selecter>
                <canvas v-el:canvas width="400" height="600"></canvas>
            </div>
        </div>
    </report-spendings>
@endsection