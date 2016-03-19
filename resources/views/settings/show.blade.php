@extends('layouts.app')
@section('content')
    <settings inline-template>
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
                    @click="changeView(link.section)"
                    :class="{
                    'active': settingsView === link.section
                    }"
                >
                    @{{ link.label }}
                </li>
            </ul>


            @include('settings.partials.company')
            @include('settings.partials.permissions')
            @include('settings.partials.rules')

            <modal></modal>

        </div>
    </div>
    </settings>
@endsection
