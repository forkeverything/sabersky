Hi {{ ucfirst($recipient->name) }},
<br>
<br>
A new Purchase Order has been made that requires your approval.
<br>
<a href="{{ env('DOMAIN') }}/purchase_orders/{{ $purchaseOrder->id }}">Please click here to view the order.</a>
<br>
<br>
@include('emails.partials.signature')
