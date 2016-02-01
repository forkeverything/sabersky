@extends('layouts.app')
@section('content')
    <div class="container" id="accept-invitation">
        <div class="page-header">
            <h1 class="page-title">Welcome to the team {{ $user->name }},</h1>
        </div>
        <p class="intro">Thank you for choosing to join us. We know you'll meet our high expectations and look forward to working
            together with you.</p>
        <section class="details">
            <h5>Details</h5>
            <p>You will be operating as a <strong>{{ ucfirst($user->role->position) }}</strong> for the <strong>{{ $user->projects()->first()->name }}</strong> project.</p>
        </section>
            <h5>Set Credentials</h5>
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
                    <button type="submit" class="btn btn-primary form-control">Accept Invitation</button>
                </div>
            </form>
        </section>
    </div>
@endsection