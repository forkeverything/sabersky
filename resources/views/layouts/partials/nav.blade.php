<nav id="top-nav" class="navbar">
    <div class="container">
        <button type="button"
                class="button-show-side-menu"
                @click.prevent.stop="toggleSideMenu">open
        </button>
        <div class="nav-brand">
            <a class="navbar-brand" href="{{ url('/') }}>
                <img src="/images/logo/logo-pji.svg" class="nav-logo"><span class="navbar-name">SaberSky</span>
            </a>
        </div>
        <ul class="navlinks-desktop">

        </ul>
        <ul class="navlink-right">
            @if(Auth::guest())
                <li><a href="{{ url('/login') }}">Login</a></li>
                <li>
                    @include('auth.partials.registration-popup')
                </li>
            @else
                <li><a href="/summary">Summary</a></li>
            @endif
        </ul>
    </div>
</nav>