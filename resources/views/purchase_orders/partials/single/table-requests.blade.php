<table v-show="tableView === 'requests'" class="po-single-request-table table table-striped table-bordered">
    <thead>
    <tr>
        <th class="heading-center"></th>
        <th class="heading-center">PR</th>
        <th>SKU</th>
        <th>Description</th>
        <th class="heading-center">Payable</th>
        <th class="heading-center">Delivery</th>
        <th class="heading-center">Qty</th>
        <th class="heading-center">Unit Price</th>
        <th class="heading-center">Total</th>
    <tr>
    </tr>
    </thead>
    <tbody>
    <template v-for="(index, lineItem) in purchaseOrder.line_items">
        <tr>
            <td>@{{ index + 1 }}</td>
            <td>#@{{ lineItem.purchase_request.number }}</td>
            <td><span v-if="lineItem.purchase_request.item.sku"></span>@{{ lineItem.purchase_request.item.sku }}
                <span v-else>-</span></td>
            <td class="col-details">
                    <span class="item-brand-name">
                        <span class="brand" v-if="lineItem.purchase_request.item.brand">@{{ lineItem.purchase_request.item.brand }}
                            - </span>
                        <span class="name">@{{ lineItem.purchase_request.item.name }}</span>
                    </span>
                    <span class="item-specification">
                        @{{ lineItem.purchase_request.item.specification }}
                    </span>
            </td>
            <td class="col-payable no-wrap">
                @{{ lineItem.payable | easyDate }}

                @can('po_payments')
                    <button v-if="! lineItem.paid" type="button" class="btn-mark-paid btn btn-small btn-solid-blue" :disabled="! (purchaseOrder.status === 'approved')">Paid</button>
                @endcan

            </td>
            <td class="col-delivery no-wrap">
                @{{ lineItem.delivery | easyDate }}

                @can('po_warehousing')
                <button v-if="lineItem.status === 'unreceived'" type="button" class="btn-mark-received btn btn-small btn-solid-green" :disabled="! (purchaseOrder.status === 'approved')">Received</button>
                @endcan

            </td>
            <td class="col-quantity no-wrap fit-to-content">
                @{{ lineItem.quantity }}
            </td>
            <td class="col-price no-wrap fit-to-content">
                @{{ formatNumber(lineItem.price, currencyDecimalPoints) }}
            </td>
            <td class="col-total no-wrap fit-to-content">
                @{{ formatNumber(lineItem.total, currencyDecimalPoints) }}
            </td>
        </tr>
    </template>
    </tbody>
</table>