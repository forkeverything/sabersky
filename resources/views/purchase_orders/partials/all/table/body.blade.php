<template v-for="order in purchaseOrders">
    <tr class="row-single-po">

        <!-- Number -->
        <td class="no-wrap col-number fit-to-content">
            <a :href="'/purchase_orders/' + order.id"
               alt="Link to single POs"
               class="underline"
            >
            #@{{ order.number }}
            </a>
        </td>

        <!-- Submitted (Date) -->
        <td class="no-wrap col-submitted-date">
            @{{ order.created_at | dateTime }}
        </td>

        <!-- Made by (user) -->
        <td>
            @{{ order.user_name | capitalize }}
        </td>

        <!-- Vendor -->
        <td class="col-vendor">
            @{{ order.vendor.name }}
        </td>


        <!-- Status -->
        <td class="col-status">
            <span class="badge po-badge"
                  :class="{
                'badge-warning': order.status === 'pending',
                'badge-success': order.status === 'approved',
                'badge-danger': order.status === 'rejected'
            }">@{{ order.status }}</span>
        </td>

        <!-- Currency -->
        <td class="no-wrap col-currency fit-to-content">
            @{{ order.currency_symbol }}
        </td>

        <!-- Total -->
        <td class="no-wrap col-total content-right fit-to-content">
             @{{ formatNumber(order.total, currencyDecimalPoints) }}
        </td>

    </tr>
</template>