<table v-show="tableView === 'items'" class="po-single-items-table table table-striped table-bordered">
    <thead>
    <tr>
        <th class="heading-center"></th>
        <th>SKU</th>
        <th>Description</th>
        <th class="heading-center">Qty</th>
        <th class="heading-center">Unit Price</th>
        <th class="heading-center">Total</th>
    <tr>
    </thead>
    <tbody>
    <template v-for="(index, item) in purchaseOrder.items">
        <tr>
            <td class="col-index no-wrap fit-to-content">@{{ index + 1 }}</td>
            <td class="col-sku no-wrap fit-to-content"><span class="item-sku">@{{  item.sku }}</span></td>
            <td class="col-details">
                        <span class="item-brand-name">
                        <span class="item-brand" v-if="item.brand">@{{ item.brand }}
                            - </span>
                        <span class="item-name">@{{ item.name }}</span>
                    </span>
                    <span class="item-specification">
                        @{{ item.specification }}
                    </span>
            </td>
            <td class="col-quantity no-wrap fit-to-content">
                @{{ item.order_quantity }}
            </td>
            <td class="col-price no-wrap fit-to-content">
                @{{ formatNumber(item.order_unit_price, currencyDecimalPoints) }}
            </td>
            <td class="col-total no-wrap fit-to-content">
                @{{ formatNumber(item.order_total, currencyDecimalPoints) }}
            </td>
        </tr>
    </template>
    </tbody>
</table>