<settings-company inline-template :settings-view.sync="settingsView" :user.sync="user">
    <div id="settings-company"  v-show="settingsView === 'company'">
        <h2>Company Information</h2>
        <p>
            View and update your company information as well as define system-wide company settings for your team.
        </p>

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
            ></textarea>
            </div>
            <div class="form-group">
                <h5>Available Currencies</h5>
                <div class="form-group available-currencies">
                    <ul v-if="companyCurrencies.length > 0" class="list-currencies list-unstyled">
                        <li v-for="currency in companyCurrencies" class="single-currency">
                            @{{ currency.country_name }} - @{{ currency.symbol }}
                            <span class="close" @click="removeCurrency(currency)" v-show="companyCurrencies.length > 1 && canRemoveCurrency(currency)"><i class="fa fa-close"></i></span>
                        </li>
                    </ul>
                    <em v-else>None</em>
                </div>
                <div class="form-group">
                    <label>Add Currency</label>
                    <currency-selecter :name.sync="selectedCurrency"></currency-selecter>
                </div>
                <div class="form-group align-end">
                    <button type="button" class="btn btn-outline-green btn-small" :disabled="! canAddCurrency" @click="addCurrency">Add Currency</button>
                </div>
            </div>
            <div class="form-group">
                <h5>Money Decimal Places</h5>
                <number-input :model.sync="currencyDecimalPoints" :placeholder="'Decimals'" :class="['form-control']"></number-input>
            </div>
            <div class="row">
                <div class="col-xs-12 align-end">
                    <button class="btn btn-solid-blue"
                    @click="updateCompany"
                    :disabled="! canUpdateCompany"
                    >
                    Update</button>
                </div>
            </div>


        </div>
    </div>
</settings-company>
