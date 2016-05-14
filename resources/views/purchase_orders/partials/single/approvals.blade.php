@if($purchaseOrder->status === 'pending')
    <span class="badge badge-warning po-badge">{{ $purchaseOrder->status }}</span>
@elseif($purchaseOrder->status === 'approved')
    <span class="badge badge-success po-badge">{{ $purchaseOrder->status }}</span>
@else
    <span class="badge badge-danger po-badge">{{ $purchaseOrder->status }}</span>
@endif

@if($purchaseOrder->status === 'pending')
    <div class="approval-controls">
        <button class="btn-approve btn btn-small btn-solid-green">Approve</button>
        <button class="btn-reject btn btn-small btn-outline-red">Reject</button>
    </div>
@endif


@if($purchaseOrder->rules)
    <ul class="attached-rules list-unstyled">
        @foreach($purchaseOrder->rules as $rule)
            <li class="rule">* {{ $rule->property->label }}
                - {{ $rule->trigger->label }}@if($rule->trigger->has_limit)@if($rule->trigger->has_currency) {{ $rule->currency->symbol }}@endif{{ number_format($rule->limit, $companyCurrencyDecimalPoints ) }}@endif</li>
        @endforeach
    </ul>
@else
    <em>none</em>
@endif
