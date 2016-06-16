@extends('layouts.app')
@section('content')
    <div class="container" id="accept-invitation">
        <h1 class="text-center">Welcome to the team {{ $user->name }},</h1>
        <p class="text-center">Thank you for choosing to join us. We know you'll meet our high expectations and look forward to working
            together with you.</p>
        <section>
            <h2>Set Credentials</h2>
            @include('errors.list')
            <form action="{{ route('acceptInvitation', $user->invite_key) }}" id="form-invited-user" method="POST">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="field-new-user-password">Password</label>
                    <input type="password" id="field-new-user-password" name="password" value="{{ old('password') }}" class="form-control">
                </div>
                <div class="form-group">
                    <label for="field-new-user-confirm-password">Confirm Password</label>
                    <input type="password" id="field-new-user-confirm-password" name="password_confirmation" value="{{ old('password_confirmation') }}" class="form-control">
                </div>
                <!-- Submit -->
                <div class="form-group">
                    <button type="submit" class="btn btn-solid-green">Accept Invitation</button>
                </div>
            </form>
        </section>
    </div>
@endsection
