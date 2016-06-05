<span class="badge po-badge"
      :class="{
            'badge-warning': purchaseOrder.status === 'pending',
            'badge-success': purchaseOrder.status === 'approved',
            'badge-danger': purchaseOrder.status === 'rejected'
       }"
>@{{ purchaseOrder.status }}
</span>


<!-- Rules Table -->
<div class="po-single-rules row" v-if="purchaseOrder.rules.length > 0">
<div class="col-sm-offset-6 col-sm-6">
    <div class="table-responsive">
        <table class="table table-rules table-bordered">
            <tbody>
            <template v-for="rule in purchaseOrder.rules">
                <po-single-rule :purchase-order.sync="purchaseOrder" :rule.sync="rule" :user="user" :xhr.sync="xhr"></po-single-rule>
            </template>
            </tbody>
        </table>
    </div>
</div>
</div>

