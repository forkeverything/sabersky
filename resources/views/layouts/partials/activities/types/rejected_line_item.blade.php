
@can('view', $activity->subject->purchaseOrder)
    <a href="{{ route('singlePurchaseOrder', $activity->subject->purchaseOrder->id) }}">
        {{ $activity->user->name }}: Rejected order for {{ $activity->subject->quantity }} at {{$activity->subject->purchaseOrder->currency->symbol}} {{ $activity->subject->price }} each
    </a>
@else
    {{ $activity->user->name }}: Rejected order for {{ $activity->subject->quantity }} at {{$activity->subject->purchaseOrder->currency->symbol}} {{ $activity->subject->price }} each
@endcan
