@extends('settings.partials.layout')

@section('settings-title')
    <h2>Settings - Billing & Subscription</h2>
    <p>
        Control your monthly subscription to Sabersky. Any changes will take effect on the next billing cycle.
    </p>
@endsection

@section('settings-content')
    <div id="settings-billing">
        <div class="part">
            <h5>Status</h5>
            @if($subscribed)
                <p class="text-success">ACTIVE</p>
            @else
                <p class="text-muted">INACTIVE</p>
            @endif
        </div>
        <div class="part">
            <h5>Plan</h5>
            <div id="subscription-plan-panel" class="{{ $plan }}">
                @if($plan == 'growth')
                    <i class="livicon-evo icon" data-options="name:rocket.svg; animated: false;"></i>
                    <h1>Growth</h1>
                    <p>$1 per month</p>
                @elseif($plan == 'enterprise')
                    <i class="livicon-evo icon" data-options="name:globe.svg; animated: false;"></i>
                    <h1>Enterprise</h1>
                    <p>$2.50 per staff per month</p>
                @else
                    <h1>None</h1>
                @endif
            </div>
        </div>
        <div id="billed-staff" class="part">
            <h1 class="count">{{ $numBilledStaff }}</h1>
            <h5>Billed Staff</h5>
            <p>You'll only be billed for active staff members.</p
        </div>
    </div>
@endsection
