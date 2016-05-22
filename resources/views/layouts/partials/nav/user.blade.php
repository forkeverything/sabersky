<nav id="top-nav" class="navbar navbar-default user-nav no-print">
    <button type="button"
            class="button-show-side-menu"
            @click.prevent.stop="toggleSideMenu"><i class="fa fa-bars"></i>
    </button>
    @if(isset($breadcrumbs))
        @include('layouts.partials.nav.partials.breadcrumbs')
    @endif
</nav>