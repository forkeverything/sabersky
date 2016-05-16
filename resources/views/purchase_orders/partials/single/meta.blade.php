<h4 class="po-number">Order #{{ $purchaseOrder->number }}</h4>
<span class="submitted-date">{{ $purchaseOrder->created_at->format('d M Y, h:i a') }}</span>
<span class="by-user">{{ $purchaseOrder->user->name }}</span>