<!-- Summary Table -->
<table class="table table-standard table-summary">
    <tbody>
        <tr>
            <td>Subtotal</td>
            <td>@{{ orderSubtotal }}</td>
            <td class="col-currency">@{{ currencySymbol }}</td>
            <td></td>
        </tr>
        <tr>
            <td><input type="text" class="form-control" placeholder="cost / discount" v-model="newCostName"></td>
            <td><input type="text" class="form-control" placeholder="amount" v-model="newCostAmount"></td>
            <td class="col-currency"><select-picker :options="[{value: '%', label: '%'}, {value: currencySymbol, label: currencySymbol }]" :name.sync="newCostType" :placeholder="'Type'"></select-picker></td>
            <td><button class="btn btn-small"><i class="fa fa-plus"></i></button></td>
        </tr>
    <tr class="row-total">
        <td>Total</td>
        <td>@{{ orderTotal }}</td>
        <td class="col-currency">@{{ currencySymbol }}</td>
        <td></td>
    </tr>
    </tbody>
</table>