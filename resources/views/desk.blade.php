@extends('layouts.app')
@section('content')
<div class="container">
    <div class="page-header">
        <h1 class="page-title">
            {{ Auth::user()->name }}'s Desk
        </h1>
    </div>
    <p class="page-intro">Here is your desk, where you will find items that require your attention, based on your position and the team that you're a part of.</p>
    @can('po_submit')
    <section class="purchase-requests">
        <h5>Open Purchase Requests</h5>
    </section>
    @endcan
</div>
@endsection