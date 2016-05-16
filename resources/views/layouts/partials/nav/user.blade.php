<nav id="top-nav" class="navbar navbar-default user-nav no-print">
    <button type="button"
            class="button-show-side-menu"
            @click.prevent.stop="toggleSideMenu"><i class="fa fa-bars"></i>
    </button>
    @if(isset($breadcrumbs))
        <div class="breadcrumbs">
            @foreach($breadcrumbs as $key => $breadcrumb)
                @if($key === count($breadcrumbs) - 1)
                    <span class="breadcrumb-link current">{!! $breadcrumb[0] !!}</span>
                @else
                    <a href="{{ $breadcrumb[1] }}" class="breadcrumb-link">{!! $breadcrumb[0] !!}</a> <span class="breadcrumb-separator">/</span>
                @endif
            @endforeach
        </div>
    @endif
</nav>