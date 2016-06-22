@extends('settings.partials.layout')

@section('settings-title')
    <h2>Settings - Company</h2>
    <p>
        View and update your company information as well as define system-wide company settings for your
        team.
    </p>
@endsection
@section('settings-content')
    <settings-company inline-template :user.sync="user">
        <div id="settings-company" class="container">

            @if(Auth::user()->hasRole('admin'))
                @include('settings.partials.delete-admin')
            @endif

            <div class="information">
                <form-errors></form-errors>
                <div class="form-group">
                    <h5>Name</h5>
                    <input type="text" v-model="user.company.name" class="form-control">
                </div>
                <div class="form-group">
                    <h5>Description</h5>
                        <textarea class="form-control autosize"
                                  v-model="user.company.description"
                        >
                        </textarea>
                </div>
                <div class="form-group">
                    <h5>Available Currencies</h5>
                    <div class="form-group available-currencies">
                        <ul v-if="availableCurrencies.length > 0" class="list-currencies list-unstyled">
                            <li v-for="currency in availableCurrencies" class="single-currency">
                                @{{ currency.country_name }} - @{{ currency.symbol }}
                                <span class="close" @click="removeCurrency(currency)" v-show="
                                    availableCurrencies.length > 1"><i class="fa fa-close"></i></span>
                            </li>
                        </ul>
                        <em v-else>None</em>
                    </div>
                    <div class="form-group">
                        <label>Add Currency</label>
                        <currency-selecter :name.sync="selectedCurrency"></currency-selecter>
                    </div>
                    <div class="form-group align-end">
                        <button type="button" class="btn btn-outline-green btn-small"
                                :disabled="! canAddCurrency" @click="addCurrency">Add Currency</button>
                    </div>
                </div>
                <div class="form-group">
                    <h5>Money Decimal Places</h5>
                    <select-picker :name.sync="user.company.settings.currency_decimal_points"
                                   :options="currencyDecimalPointsOptions"></select-picker>
                </div>
                <div class="row">
                    <div class="col-xs-12 align-end">
                        <button class="btn btn-solid-blue"
                        @click="updateCompany"
                        :disabled="! canUpdateCompany"
                        >
                        Update Changes</button>
                    </div>
                </div>
            </div>
        </div>
    </settings-company>

@endsection

