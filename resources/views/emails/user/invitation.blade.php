Hi {{ ucfirst($recipient->name) }},
<br>
<br>
You've been invited by {{ $sender->name }} to join {{ $sender->company->name }} on Sabersky for the role of {{ ucfirst($recipient->role->position) }}.
<br>
<br>
If you believe this has been sent as a mistake please ignore this message or <a href="http://www.sabersky.com">check us out anyway.</a>
<br>
<br>
<strong><a href="{{ env('DOMAIN') }}/accept_invitation/{{ $recipient->invite_key }}">Click here to complete the sign-up process.</a></strong>
<br>
<br>
Sabersky is a purchasing system built for rapid growth businesses. For more information, please visit us at http://www.sabersky.com
<br>
<br>
@include('emails.partials.signature')