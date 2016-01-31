@extends('layouts.app')
@section('content')
    <div class="container">
        <a href="{{ route('dashboard') }}" class="link-underline"><i class="fa  fa-arrow-left fa-btn"></i>Back to Dashboard</a>
        <div class="page-header">
            <h1 class="page-title">Purchase Requests</h1>
        </div>
        <p>This is where you can find purchase requests made by Engineers / Planners.</p>
    </div>
@endsection