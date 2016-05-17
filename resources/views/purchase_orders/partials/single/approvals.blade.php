<span class="badge po-badge"
      :class="{
            'badge-warning': purchaseOrder.status === 'pending',
            'badge-success': purchaseOrder.status === 'approved',
            'badge-danger': purchaseOrder.status === 'rejected'
       }"
>@{{ purchaseOrder.status }}
</span>


<div class="approval-controls" v-if="purchaseOrder.status === 'pending'">
    <button class="btn-approve btn btn-small btn-solid-green">Approve</button>
    <button class="btn-reject btn btn-small btn-outline-red">Reject</button>
</div>


<ul class="attached-rules list-unstyled" v-if="purchaseOrder.rules.length > 0">

    <li class="rule"
        v-for="rule in purchaseOrder.rules"
    >* @{{ rule.property.label }}
        - @{{ rule.trigger.label }} <span v-if="rule.trigger.has_limit">@{{ formatRuleLimit(rule) }}</span></li>

</ul>

<!-- Rules Table -->
<table class="table table-striped table-bordered">
    <tbody>
    <template v-for="rule in purchaseOrder.rules">
        <tr>
            <td class="col-description">
                @{{ rule.property.label }} - @{{ rule.trigger.label }} <span v-if="rule.trigger.has_limit">@{{ formatRuleLimit(rule) }}</span>
            </td>
            <td class="col-check fit-to-content">

            </td>
        </tr>
    </template>
    </tbody>
</table>


