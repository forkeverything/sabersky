<!-- Purchase Orders Table -->
<table class="table table-standard table-purchase-orders-all table-hover">
    <thead>
        <tr>
            @include('purchase_orders.all.table.headings')
        </tr>
    </thead>
    <tbody>
        @include('purchase_orders.all.table.body')
    </tbody>
</table>