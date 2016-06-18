Hi {{ ucfirst($recipient->name) }},
<br>
<br>
A new Purchase Request has been made for {{ ucfirst($purchaseRequest->project->name) }} Project by {{ ucfirst($requester->name) }}.
<br>
<br>
<a href="{{ env('DOMAIN') }}/purchase_requests/{{ $purchaseRequest->id }}">Click here to view the details of the request.</a>
<br>
<br>
@include('emails.partials.signature')
