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
        <td class="col-status"
            :class="{
                'warning': order.status === 'pending',
                'success': order.status === 'approved',
                'danger': order.status === 'rejected'
            }"
        >
            @{{ order.status }}
        </td>

        <!-- Paid (Approved only) -->
        <td v-show="params.status === 'approved'"
            class="col-paid"
            :class="{
                'success': order.percentage_paid_line_items == 1,
                'warning': 0 < order.percentage_paid_line_items && order.percentage_paid_line_items < 1,
                'danger': order.num_paid_line_items == 0
            }"
        >
            @{{ order.num_paid_line_items }} / @{{ order.num_line_items }}
        </td>

        <!-- Received (Approved Only) -->
        <td v-show="params.status === 'approved'"
            class="col-received"
            :class="{
                'success': order.percentage_received_line_items == 1,
                'warning': 0 < order.percentage_received_line_items && order.percentage_received_line_items < 1,
                'danger': order.num_received_line_items == 0
            }"
        >
            @{{ order.num_received_line_items }} / @{{ order.num_line_items }}
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