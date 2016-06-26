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
            <img src="/images/logo/logo-blue.jpg" class="nav-logo">
        </a>

        <ul class="navbar-dropdown animated"
            v-show="showNavDropdown"
            @click.stop=""
            transition="fade-slide"
        >
            <li><a href="{{ url('/login') }}">Login</a></li>
            <li>@include('auth.register.popup')</li>
        </ul>
    </div>
</nav>