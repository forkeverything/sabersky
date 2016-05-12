<template v-for="order in purchaseOrders">
    <tr class="row-single-po">

        <!-- Number -->
        <td class="no-wrap col-number fit-to-content">
            #@{{ order.number }}
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
            <span :class="order.status">@{{ order.status }}</span>
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