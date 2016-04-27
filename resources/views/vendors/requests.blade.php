@extends('layouts.app')
@section('content')
    <vendor-requests inline-template>
        <div class="container" id="vendor-requests">
            <div class="page-body">
                    <ul class="list-unstyled list-pending-requests" v-if="pendingVendors && pendingVendors.length > 0">
                            <li class="single-requests" v-for="vendor in pendingVendors">
                                <div class="thumbnail-name">
                                    <span class="company-name">@{{ vendor.base_company.name }}</span>
                                </div>
                                <div class="request-controls">
                                    <button type="button" class="btn-solid-blue btn-small" @click="respondRequest(vendor, 'verify')">Verify</button>
                                    <button type="button" class="btn-outline-grey btn-small" @click="respondRequest(vendor, 'dismiss')">Dismiss</button>
                                </div>
                            </li>
                    </ul>
                    <div class="empty-stage" v-else>
                        <i class="fa fa-rocket"></i>
                        <h3>No pending vendor requests at this time</h3>
                        <p>Ask your customers to register on SaberSky and link you as a vendor for more integrated and
                            efficient transactions.</p>
                    </div>
            </div>
        </div>
    </vendor-requests>
@endsection