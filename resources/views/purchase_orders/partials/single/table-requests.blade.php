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
            <td class="text-center">@{{ index + 1 }}</td>
            <td class="fit-to-content">#@{{ lineItem.purchase_request.number }}</td>
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
                <span class="payable-date"
                      :class="{
                        'paid': lineItem.paid
                      }"
                >
                    @{{ lineItem.payable | easyDate }}
                </span>
                @can('po_payments')
                <button v-if="! lineItem.paid"
                        type="button"
                        class="btn-mark-paid btn btn-small btn-solid-blue"
                        :disabled="! (purchaseOrder.status === 'approved')"
                @click="markPaid(lineItem)"
                >
                Paid
                </button>
                <span v-else class="paid">Paid</span>
                @else
                    <span v-if="lineItem.paid" class="paid">Paid</span>
                    <span v-else class="unpaid">Unpaid</span>
                    @endcan


            </td>
            <td class="col-delivery no-wrap">
                 <span class="delivery-date"
                       :class="lineItem.status"
                 >
                    @{{ lineItem.delivery | easyDate }}
                </span>

                @can('po_warehousing')
                <div v-if="lineItem.status === 'unreceived'" class="mark-received">
                    <po-mark-received-popover :purchase-order.sync="purchaseOrder"
                                              :line-item.sync="lineItem"></po-mark-received-popover>
                </div>
                <span v-else :class="lineItem.status">@{{ lineItem.status | capitalize }}</span>
                @else
                    <span :class="lineItem.status">@{{ lineItem.status | capitalize }}</span>
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