Hi {{ ucfirst($recipient->name) }},
<br>
<br>
You've been invited by {{ $sender->name }} to join {{ $sender->company->name }} on Sabersky for the role of {{ ucfirst($recipient->role->position) }}.
<br>
<br>
If you believe this has been sent as a mistake, you can go ahead and ignore this message.
<br>
<br>
<strong><a href="{{ env('DOMAIN') }}/accept_invitation/{{ $recipient->invite_key }}">Click here to complete the sign-up process.</a></strong>
<br>
<br>
Sabersky is a purchasing system built for rapid growth businesses. For more information, please visit us at http://www.sabersky.com
<br>
<br>
@include('emails.partials.signature')