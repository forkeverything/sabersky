<!-- Summary Table -->
<table class="table table-standard table-summary">
    <tbody>
    <tr>
        <td class="col-title">Subtotal</td>
        <td class="col-amount">@{{ formatNumber(purchaseOrder.subtotal, currencyDecimalPoints) }}</td>
        <td class="col-currency fit-to-content">@{{ purchaseOrder.currency_symbol }}</td>
    </tr>
        <template v-for="cost in purchaseOrder.additional_costs">
            <tr>
                <td class="col-title">@{{ cost.name }}</td>
                <template v-if="cost.type === '%'">
                    <td class="col-amount">@{{ cost.amount }}</td>
                    <td class="col-currency fit-to-content">%</td>
                </template>
                <template v-else>
                    <td class="col-amount" v-else>@{{ formatNumber(cost.amount, currencyDecimalPoints)}}</td>
                    <td class="col-currency fit-to-content">@{{ purchaseOrder.currency_symbol }}</td>
                </template>
            </tr>
        </template>
    <tr class="row-title">
        <td class="col-title">Total Cost</td>
        <td class="col-amount">@{{ formatNumber(purchaseOrder.total, currencyDecimalPoints) }}</td>
        <td class="col-currency fit-to-content">@{{ purchaseOrder.currency_symbol }}</td>
    </tr>
    </tbody>
</table>