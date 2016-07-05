@extends('layouts.app')

@section('content')
    <div id="login_body">
        <div class="bg_overlay"></div>
        <div class="login_wrap">
            <form id="form_login" class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                {{ csrf_field() }}
                <h1 class="auth_heading"><strong>Log</strong> In</h1>
                <span class="auth_tagline">Better purchasing, better business.</span>

                @if($errors->any())
                    <div class="login_errors exists">
                        <ul class="list-unstyled">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="auth_fields">
                    <input id="login_email" type="email" class="form-control" name="email" value="{{ old('email') }}"
                           required>
                    <label alt="email" placeholder="Email" class="label_auth"></label>
                </div>

                <div class="auth_fields">
                    <input id="login_password" autocomplete="false" required type="password" class="form-control"
                           name="password">
                    <label alt="password" placeholder="Password" class="label_auth"></label>
                </div>

                <button id="button_login" type="submit" class="btn btn-solid-blue auth_button">
                    Login
                </button>
            </form>
        </div>
    </div>
    @include('layouts.partials.registration-popup')
@endsection
