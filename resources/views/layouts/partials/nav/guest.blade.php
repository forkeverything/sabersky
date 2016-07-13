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
                <img src="/images/logo/logo-blue-600.jpg" class="nav-logo">
            </a>

            <ul class="navbar-dropdown"
                v-show="showNavDropdown"
                @click.stop="">
                <li><a href="{{ url('/login') }}">Login</a></li>
                <li>
                    <a href="/register" alt="Register Link" class="nav-register">
                        <i class="fa fa-power-off"></i>
                    </a>
                </li>
        </div>
    </nav>