<side-menu inline-template :user="user">
    <nav id="side-menu"
         class="showing-menu-overlay animated"
         v-show="show"
         transition="slide"
    >
        <div class="side-menu-top">
            <div class="user popover-container">
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
                <div class="user-popup popover-content bottom animated"
                     v-show="userPopup"
                     transition="fade-slide"
                >
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/logout') }}">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <ul class="nav-links">
            <li class="single-list-item">
                <a href="/projects">
                    <i class="fa fa-flash"></i>Projects
                </a>
            </li>
            <li class="single-list-item">
                <a href="/team">
                    <i class="fa fa-users"></i>Team
                </a>
            </li>
            <li class="single-list-item">
                <a class="show-sublinks"
                   @click.prevent="expand('purchase')"
                   :class="{
                        'expanded': expandedSection === 'purchase'
                    }"
                >
                    <i class="fa fa-shopping-basket"></i> Purchase
                </a>
                <ul class="list-unstyled list-sublinks"
                    :class="{
                        'expanded': expandedSection === 'purchase'
                    }"
                >
                    <li class="sublink">
                        <a href="/purchase_requests">
                            Requests
                        </a>
                    </li>
                    <li class="sublink">
                        <a href="/purchase_orders">
                            Orders
                        </a>
                    </li>
                </ul>
            </li>
            <li class="single-list-item">
                <a href="/vendors">
                    <i class="fa fa-truck"></i>
                    Vendors
                </a>
            </li>
            <li class="single-list-item">
                <a href="/items">
                    <i class="fa fa-legal"></i>
                    Items
                </a>
            </li>
            @can('report_view')
                <li class="single-list-item">
                    <a class="show-sublinks"
                       @click.prevent="expand('reports')"
                       :class="{
                        'expanded': expandedSection === 'reports'
                    }"
                    >
                        <i class="fa fa-bar-chart"></i>
                        Reports
                    </a>
                    <ul class="list-unstyled list-sublinks"
                        :class="{
                        'expanded': expandedSection === 'reports'
                    }"
                    >
                        <li class="sublink">
                            <h3>Spendings</h3>
                            <ul class="list-unstyled">
                                <li><a href="/reports/spendings/projects">Projects</a></li>
                                <li><a href="/reports/spendings/vendors">Vendors</a></li>
                                <li><a href="/reports/spendings/employees">Staff</a></li>
                                <li><a href="/reports/spendings/items">Items</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
            @endcan
            @can('settings_change')
                <li class="single-list-item">
                    <a class="show-sublinks"
                       @click.prevent="expand('settings')"
                       :class="{
                        'expanded': expandedSection === 'settings'
                    }"
                    >
                        <i class="fa fa-gears"></i> Settings
                    </a>
                    <ul class="list-unstyled list-sublinks"
                        :class="{
                        'expanded': expandedSection === 'settings'
                    }"
                    >
                        <li class="sublink">
                            <a href="/settings/company">
                                Company
                            </a>
                        </li>
                        <li class="sublink">
                            <a href="/settings/permissions">
                                Permissions
                            </a>
                        </li>
                        <li class="sublink">
                            <a href="/settings/rules">
                                Rules
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan
        </ul>
    </nav>
</side-menu>