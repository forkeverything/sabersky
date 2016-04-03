@extends('layouts.app')
@section('content')
    <settings inline-template :user.sync="user">
        <div class="container" id="system-settings">
            <a href="{{ route('dashboard') }}" class="back-link no-print"><i class="fa  fa-arrow-left fa-btn"></i>Dashboard</a>
            <div class="page-header">
                <h1 class="page-title">
                    System Settings
                </h1>
                <p class="page-intro">Change Application settings to determine what needs approval for whom. Defaults
                    have
                    been automatically set for you.</p>
            </div>
            <div class="custom-tabs">

                <ul class="nav nav-tabs" role="tablist" v-autofit-tabs>
                    <li class="clickable"
                        role="presentation"
                        v-for="link in navLinks"
                    @click="changeView(link.section)"
                    :class="{
                                'active': settingsView === link.section
                            }"
                    >
                    <a href="#settings-@{{ link.section }}"
                       aria-controls="settings-@{{ link.section }}"
                       role="tab"
                       data-toggle="tab"
                    >
                        @{{ link.label }}
                    </a>
                    </li>
                </ul>

                <div class="tab-content">
                    @include('settings.partials.company')
                    @include('settings.partials.permissions')
                    @include('settings.partials.rules')
                </div>

            </div>
        </div>
    </settings>
@endsection
