@extends('layouts.app')
@section('content')
    <settings inline-template>
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
            <div class="page-body">


                <div>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist" v-tabs>
                        <li role="presentation" class="active"><a href="#tab1" aria-controls="tab1" role="tab"
                                                                  data-toggle="tab">tab1</a></li>
                        <li role="presentation"><a href="#tab2" aria-controls="tab2" role="tab"
                                                   data-toggle="tab">tab2</a></li>
                        <li role="presentation"><a href="#tab3" aria-controls="tab3" role="tab"
                                                   data-toggle="tab">tab3</a></li>
                        <li role="presentation"><a href="#tab4" aria-controls="tab4" role="tab"
                                                   data-toggle="tab">tab4</a></li>
                        <li role="presentation"><a href="#tab5" aria-controls="tab5" role="tab"
                                                   data-toggle="tab">tab5</a></li>
                        <li role="presentation"><a href="#tab6" aria-controls="tab6" role="tab"
                                                   data-toggle="tab">tab6</a></li>
                        <li role="presentation"><a href="#tab7" aria-controls="tab7" role="tab"
                                                   data-toggle="tab">tab7</a></li>
                        <li role="presentation"><a href="#tab8" aria-controls="tab8" role="tab"
                                                   data-toggle="tab">tab8</a></li>
                        <li role="presentation"><a href="#tab9" aria-controls="tab9" role="tab"
                                                   data-toggle="tab">tab9</a></li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="tab1">tab1</div>
                        <div role="tabpanel" class="tab-pane" id="tab2">tab2</div>
                        <div role="tabpanel" class="tab-pane" id="tab3">tab3</div>
                        <div role="tabpanel" class="tab-pane" id="tab4">tab4</div>
                        <div role="tabpanel" class="tab-pane" id="tab5">tab5</div>
                        <div role="tabpanel" class="tab-pane" id="tab6">tab6</div>
                        <div role="tabpanel" class="tab-pane" id="tab7">tab7</div>
                        <div role="tabpanel" class="tab-pane" id="tab8">tab8</div>
                        <div role="tabpanel" class="tab-pane" id="tab9">tab9</div>

                    </div>
                </div>


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

            </div>
        </div>
    </settings>
@endsection
