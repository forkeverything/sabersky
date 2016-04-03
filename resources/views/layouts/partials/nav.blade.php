@if(Auth::user())
    <nav id="top-nav" class="navbar navbar-default user-nav">
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
@else
    <nav id="top-nav" class="navbar navbar-default guest-nav">
        <div class="container">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle"
                    @click.stop="toggleNavDropdown"
            >
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-logo" href="{{ url('/') }}">
                <img src="/images/logo/logo-pji.svg" class="nav-logo">
            </a>

            <ul class="navbar-dropdown animated"
                v-show="showNavDropdown"
                @click.stop=""
                transition="fade-slide"
            >
                <li><a href="{{ url('/info/pricing') }}">Pricing</a></li>
                <li><a href="{{ url('/login') }}">Login</a></li>
            </ul>

            <ul class="navbar-right list-unstyled">
                <li>
                    @include('auth.partials.registration-popup')
                </li>
            </ul>
        </div>
    </nav>


@endif