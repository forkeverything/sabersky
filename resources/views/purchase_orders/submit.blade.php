@extends('layouts.app')
@section('content')
    <purchase-orders-submit inline-template>
        <div class="container" id="purchase-orders-submit">
            <div class="page-body">
                <section class="step project">
                    <h5>Project</h5>
                    <user-projects-selecter :name.sync="projectID"></user-projects-selecter>
                </section>
                @include('purchase_orders.partials.submit.select-pr')
            </div>
        </div>
    </purchase-orders-submit>
@endsection
