<template v-for="order in purchaseOrders">
    <tr class="row-single-po">

        <!-- Number -->
        <td class="no-wrap col-number fit-to-content">
            #@{{ order.number }}
        </td>

        <!-- Vendor -->
        <td class="col-vendor">
            @{{ order.vendor.name }}
        </td>

        <!-- No. Line Items -->
        <td class="no-wrap col-num-line-items content-center fit-to-content">
            @{{ order.num_line_items }}
        </td>

        <!-- Currency -->
        <td class="no-wrap col-currency fit-to-content">
            @{{ order.currency_symbol }}
        </td>

        <!-- Total -->
        <td class="no-wrap col-total content-right fit-to-content">
             @{{ formatNumber(order.total_query, currencyDecimalPoints) }}
        </td>

        <!-- Submitted (Date) -->
        <td class="no-wrap col-submitted-date fit-to-content">
            @{{ order.created_at | diffHuman }}
        </td>

        <!-- Made by (user) -->
        <td class="no-wrap fit-to-content">
            @{{ order.user_name | capitalize }}
        </td>

    </tr>
</template>