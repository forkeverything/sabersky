<!-- Summary Table -->
<table class="table table-standard table-summary">
    <tbody>
        <tr>
            <td class="col-title">Subtotal</td>
            <td class="col-amount">@{{ formatNumber(orderSubtotal) }}</td>
            <td class="col-currency">@{{ currencySymbol }}</td>
        </tr>
        <template v-for="cost in additionalCosts">
            <tr class="row-added-costs">
                <td class="col-title">
                    @{{ cost.name }}
                    <button type="button" class="close" aria-label="Close" @click="removeAdditionalCost(cost)"><span aria-hidden="true">&times;</span></button>
                </td>
                <td class="col-amount">@{{ formatNumber(cost.amount) }}</td>
                <td class="col-currency">@{{ cost.type }}</td>
            </tr>
        </template>
        <tr class="row-inputs">
            <td class="col-title">
                <input type="text" class="form-control" placeholder="cost / discount" v-model="newCostName">
            </td>
            <td class="col-amount">
                <number-input :model.sync="newCostAmount" :placeholder="'amount'" :class="['form-control']"></number-input>
            </td>
            <td class="col-currency"><select-picker :options="[{value: '%', label: '%'}, {value: currencySymbol, label: currencySymbol }]" :name.sync="newCostType"></select-picker></td>
        </tr>
        <tr v-show="canAddNewCost" class="row-add-button">
            <td></td>
            <td></td>
            <td><button type="button" class="btn btn-small btn-add-cost btn-outline-blue" @click="addAdditionalCost"><i class="fa fa-plus"></i> Cost / Discount</button></td>
        </tr>
    <tr class="row-total">
        <td class="col-title">Total Cost</td>
        <td class="col-amount">@{{ formatNumber(orderTotal) }}</td>
        <td class="col-currency">@{{ currencySymbol }}</td>
    </tr>
    </tbody>
</table>

