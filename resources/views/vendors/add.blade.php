@extends('layouts.app')

@section('content')
<vendors-add-new inline-template>
    <div id="vendors-add-new" class="container">

        <div class="custom-tabs">
            <ul class="nav nav-tabs" role="tablist" v-autofit-tabs>
                <li class="clickable"
                    role="presentation"
                    v-for="link in navLinks"
                @click="changeTab(link)"
                :class="{
                                'active': currentTab === link
                            }"
                >
                <a href="#settings-@{{ link }}"
                   aria-controls="settings-@{{ link }}"
                   role="tab"
                   data-toggle="tab"
                >
                    @{{ link | capitalize }}
                </a>
                </li>
            </ul>

            <div class="tab-content">
                @include('vendors.partials.add.search')
                @include('vendors.partials.add.custom')
            </div>

        </div>
    </div>
</vendors-add-new>
@endsection
