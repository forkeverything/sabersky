@extends('layouts.app')
@section('content')
    <div class="container" id="system-settings">
        <a href="{{ route('dashboard') }}" class="back-link no-print"><i class="fa  fa-arrow-left fa-btn"></i>Dashboard</a>
        <div class="page-header">
            <h1 class="page-title">
                System Settings
            </h1>
            <p class="page-intro">Change Application settings to determine what needs approval for whom. Defaults have
                been automatically set for you.</p>
        </div>
        <div class="page-body">

            <ul id="settings-nav" class="list-unstyled list-inline">
                <li class="clickable"
                    v-for="link in navLinks"
                    @click="changeView(link.component)"
                    :class="{
                    'active': settingsView === link.component
                    }"
                >
                    @{{ link.label }}
                </li>
            </ul>

            <component :is="settingsView"
                       :ajax-ready.sync="ajaxReady"
                       :modal-title.sync="modalTitle"
                       :modal-body.sync="modalBody"
                       :modal-mode.sync="modalMode"
                       :modal-function.sync="modalFunction"
            ></component>

            @include('settings.partials.permissions')
            @include('settings.partials.rules')

            @include('layouts.partials.modal')

        </div>
    </div>
@endsection
@section('scripts.footer')
    <script src="{{ asset('/js/page/settings/show.js') }}"></script>
@endsection