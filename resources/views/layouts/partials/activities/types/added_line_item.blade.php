
@can('view', $activity->subject->purchaseOrder)
<a href="{{ route('singlePurchaseOrder', $activity->subject->purchaseOrder->id) }}">
    {{ $activity->user->name }}: Fulfilled {{ $activity->subject->quantity }} at {{$activity->subject->purchaseOrder->currency->symbol}} {{ $activity->subject->price }} each
</a>
@else
    {{ $activity->user->name }}: Fulfilled {{ $activity->subject->quantity }} at {{$activity->subject->purchaseOrder->currency->symbol}} {{ $activity->subject->price }} each
@endcan
