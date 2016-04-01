<side-menu inline-template>
        <nav id="side-menu"
             class="showing-menu-overlay animated"
             v-show="show"
             transition="slide"
            @click="toggleShow"
        >
            <div class="side-menu-top">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <span class="navbar-name">SaberSky</span>
                </a>
            </div>
            <ul class="side-menu-links">
                <li class="single-list-item"><a href="/">Home</a></li>
                <li class="single-list-item"><a href="/about/">About us</a></li>
                <li class="single-list-item"><a href="/contact/">Contact</a></li>
            </ul>
        </nav>
</side-menu>