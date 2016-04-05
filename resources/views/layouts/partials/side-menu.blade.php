<side-menu inline-template :user="user">
    <nav id="side-menu"
         class="showing-menu-overlay animated"
         v-show="show"
         transition="slide"
    >
        <div class="side-menu-top">
            <a class="navbar-brand" href="{{ url('/') }}">
                <span class="navbar-name">SaberSky</span>
            </a>
        </div>
        <ul class="nav-links">
            <li class="single-list-item">
                <a href="/projects">
                    <i class="fa fa-flash icon-dashboard"></i>Projects
                </a>
            </li>
            <li class="single-list-item">
                <a href="/team">
                    <i class="fa fa-users"></i>Team
                </a>
            </li>
            <li class="single-list-item">
                <a href="/purchase_requests">
                    <i class="fa fa-shopping-basket icon-dashboard"></i>
                    Purchase Requests
                </a>
            </li>
            <li class="single-list-item">
                <a href="/vendors">
                    <i class="fa fa-truck icon-dashboard"></i>
                    Vendors
                </a>
            </li>
            <li class="single-list-item">
                <a href="/items">
                    <i class="fa fa-legal icon-dashboard"></i>
                    Items
                </a>
            </li>
            <li class="single-list-item">
                <a href="/purchase_orders">
                    <i class="fa fa-clipboard icon-dashboard"></i>
                    Purchase Orders
                </a>
            </li>
            @can('report_view')
            <li class="single-list-item">
                <a href="/reports">
                    <i class="fa fa-bar-chart icon-dashboard"></i>
                    Reports
                </a>
            </li>
            @endcan
            @can('settings_change')
            <li class="single-list-item">
                <a href="/settings">
                    <i class="fa fa-gears icon-dashboard"></i>
                    Settings
                </a>
            </li>
            @endcan
        </ul>
        <div class="side-menu-bottom">
            <div class="user">
                <a href="#"
                   class="button-user-popup"
                   @click.stop="toggleUserPopup"
                   v-show="finishedCompiling"
                >
                <span class="user-avatar">
                    @{{ userInitials }}
                </span>
                <div class="name-company">
                    <span class="name">{{ Auth::user()->name }}</span>
                    <span class="company">@{{ companyName }}</span>
                </div>
                </a>
                <div class="user-popup"
                     v-show="userPopup"
                >
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/logout') }}">Logout</a></li>
                    </ul>
                    <div class="caret-down"></div>
                </div>
            </div>
        </div>
    </nav>
</side-menu>