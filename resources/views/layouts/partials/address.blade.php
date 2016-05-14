<div class="address">
    @if($address->contact_person)
        <span class="contact_person display-block">{{ $address->contact_person }}</span>
    @endif
    @if($company)
        <span class="company_name display-block">{{ $company->name }}</span>
    @endif
    <span class="address_1 display-block">{{ $address->address_1 }}</span>
    <span class="address_2 display-block">{{ $address->address_2 }}</span>
    <span class="city">{{ $address->city }}</span>,
    <span class="zip">{{ $address->zip }}</span>
    <div class="state-country display-block">
        <span class="state">{{ $address->state }}</span>,
        <span class="country">{{ $address->country }}</span><br>
        <span class="phone"><abbr title="Phone">P:</abbr> {{ $address->phone }}</span>
    </div>
</div>