Pusaka Jaya Procurement System
------------------------------------------------------------------------------------------------------
Hello {{ $user->name }},
<br>
<br>
You have been invited to join Pusaka's Procure System as a <span class="capitalize">{{ $user->role->position }}</span> for the
<strong>{{ $user->projects()->first()->name }}</strong> project.
<br>
If you would like to Accept, please click the link below.
<br>
<a href="{{ env('DOMAIN') }}/accept_invitation/{{ $user->invite_key }}">http://procurement.pusakagroup.com/accept_invitation/{{ $user->invite_key }}</a>
<br>
<br>
The link will expire in 72 hours and a New Invitation will be required.
------------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------
This is an auto-generated email, please do not reply.