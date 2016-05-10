    @extends('layouts.app')
@section('content')
    <purchase-orders-all inline-template>
        <div id="purchase-orders-all" class="container">
            @can('po_submit')
            <div class="top align-end">
                <a href="{{ route('getSubmitPOForm') }}">
                    <button class="btn btn-solid-green" id="button-submit-purchase-order">Create Order</button>
                </a>
            </div>
            @endcan
            <div class="custom-tabs">

                <ul class="nav nav-tabs" role="tablist" v-autofit-tabs>
                    <li class="clickable"
                        role="presentation"
                        v-for="status in statuses"
                    @click="changeStatus(status)"
                    :class="{
                                'active': activeStatus === status
                            }"
                    >
                    <a href="#settings-@{{ status }}"
                       aria-controls="settings-@{{ status }}"
                       role="tab"
                       data-toggle="tab"
                       :class="status"
                    >
                        @{{ status | capitalize }}
                    </a>
                    </li>
                </ul>

                <div class="tab-content">
                   <h1>same div!</h1>
                </div>

            </div>
        </div>
    </purchase-orders-all>
@endsection
