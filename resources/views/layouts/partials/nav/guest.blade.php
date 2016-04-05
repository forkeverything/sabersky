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