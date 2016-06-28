@extends('settings.partials.layout')

@section('settings-header')
    <h1>Settings - Billing & Subscription</h1>
    <p>
        Control your monthly subscription to Sabersky. Any changes will take effect on the next billing cycle.
    </p>
@endsection

@section('settings-content')
    <settings-billing inline-template>
        <div id="settings-billing">
            <section class="status">
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
                    <h1 class="count">{{ $numActiveStaff }}</h1>
                    <h5>Active Staff</h5>
                    <em class="info-billed-quantity">* Only active staff members count towards billed quantity</em>
                    <p>You will be billied
                        <strong>
                            @if($plan === 'growth')
                                $1
                            @elseif($plan === 'enterprise')
                                ${{ $numActiveStaff * 2.50}}
                            @else
                                $0
                            @endif
                        </strong>
                        on the next billing cycle
                    </p>
                </div>
            </section>
            <section class="subscription">
                <h5>Subscription</h5>
                @if($subscribed)
                    @if(! $subscription->onGracePeriod())
                        <p>Your subscription will be active until your next billing cancel. Once cancelled, <strong>all
                                staff members will not be able to access Sabersky services until re-activation.</strong>
                        </p>
                        <form action="/subscription/cancel" method="POST">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-outline-red">Cancel Subscription</button>
                        </form>
                    @else
                        <p>Already cancelled, will expire on:</p>
                        <h1>
                             {{ $subscription->ends_at->format('d F Y') }}
                        </h1>
                        <form action="/subscription/resume" method="POST">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-solid-blue">Resume Subscription</button>
                        </form>
                    @endif
                @else
                    <p>Your Sabersky service is currently <strong>inactive</strong>. To start using the service again please re-activate your subscription by adding a credit card.</p>
                    <button type="button" class="btn btn-solid-green" @click="toggleCreditCardForm" v-show="
                    ! showCreditCardForm">Activate</button>
                    <div v-show="showCreditCardForm">
                        <form-credit-card></form-credit-card>
                        <div class="align-end">
                            <a class="link-cancel-add-card" href="#" @click.prevent="toggleCreditCardForm">Cancel</a>
                        </div>
                    </div>
                @endif
            </section>

        </div>
    </settings-billing>
@endsection
