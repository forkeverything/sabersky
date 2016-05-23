<div class="controls">
    <div class="currency-selecter">
        <h5>Currency</h5>
        <company-currency-selecter :id.sync="currencyId" :currencies="companyCurrencies"
                                   :currency-object.sync="currency"></company-currency-selecter>
    </div>
    <div class="date-range-filter">
        <div v-show="dateMin || dateMax" class="clear-range">
            <a class="clickable dotted" @click="clearDateRange">clear</a>
        </div>
        <date-range-field :min.sync="dateMin" :max.sync="dateMax"></date-range-field>
    </div>
</div>