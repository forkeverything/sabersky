@if($purchaseOrder->status === 'pending')
    <span class="badge badge-warning po-badge">{{ $purchaseOrder->status }}</span>
@elseif($purchaseOrder->status === 'approved')
    <span class="badge badge-success po-badge">{{ $purchaseOrder->status }}</span>
@else
    <span class="badge badge-danger po-badge">{{ $purchaseOrder->status }}</span>
@endif

@if($purchaseOrder->rules)
    <ul class="attached-rules list-unstyled">
        @foreach($purchaseOrder->rules as $rule)
            <li class="rule">{{ $rule }}</li>
        @endforeach
    </ul>
@endif

