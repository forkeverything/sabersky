
Hi {{ $user->name }},
<br>
<br>
Thank you for joining Sabersky. We hope that you'll love our services and enjoy a streamlined purchasing system for your operations.
<br>
<br>
<span style="font-size: 18px; font-weight: bold;">Where to go from here?</span>
<br>
<br>
Tailor your purchasing system to your needs by going through the following steps:
<br>
<br>
1. <a href="{{ env('DOMAIN') }}/settings/company">Set company preferences and fill out some general info</a>
<br>
2. <a href="{{ env('DOMAIN') }}/settings/roles">Create Staff roles for your company</a>
<br>
3. <a href="{{ env('DOMAIN') }}/settings/purchasing">Define rules for purchasing control</a>
<br>
4. <a href="{{ env('DOMAIN') }}/staff/add">Send out invites to staff members you wish to enroll</a>
<br>
5. <a href="{{ env('DOMAIN') }}/vendors/add">Add a Vendor</a>
<br>
6. <a href="{{ env('DOMAIN') }}/items">Add an Item that would be requested / ordered</a>
<br>
7. <a href="{{ env('DOMAIN') }}/purchase_requests/make">Make a Purchase Request</a>
<br>
<br>
@include('emails.partials.signature')