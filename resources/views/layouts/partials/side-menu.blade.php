<side-menu inline-template :user="user">
    <nav id="side-menu"
         class="showing-menu-overlay animated"
         v-show="show"
         transition="slide"
    >
        <div class="side-menu-top">
            <div class="user popover-container">
                <div class="company">@{{ companyName }}</div>
                <a href="#"
                   class="button-user-popup"
                   @click.stop="toggleUserPopup"
                   v-show="finishedCompiling"
                >
                    <profile-photo :user="user"></profile-photo>
                    <div class="name">@{{ user.name }}</div>
                </a>
                <div class="user-popup popover-content bottom animated"
                     v-show="userPopup"
                     transition="fade-slide"
                >
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/user/profile') }}">Profile</a></li>
                        <li><a href="{{ url('/logout') }}">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <ul class="nav-links">
            <li class="single-list-item">
                <a href="/projects">
                    <i class="livicon-evo icon-sidemenu" data-options="name:lightning.svg; repeat: loop; eventOn: parent; style: solid;"></i>
                    Projects
                </a>
            </li>
            <li class="single-list-item">
                <a href="/staff">
                    <i class="livicon-evo icon-sidemenu" data-options="name:users.svg; repeat: loop; eventOn: parent; style: solid;"></i>
                    Staff
                </a>
            </li>
            <li class="single-list-item">
                <a class="show-sublinks"
                   @click.prevent="expand('purchase')"
                   :class="{
                        'expanded': expandedSection === 'purchase'
                    }"
                >
                    <i class="livicon-evo icon-sidemenu" data-options="name:shoppingcart-in.svg; repeat: loop; eventOn: parent; style: solid;"></i>
                    Purchase
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
                    <i class="livicon-evo icon-sidemenu" data-options="name:building.svg; repeat: loop; eventOn: parent; style: solid;"></i>
                    Vendors
                </a>
            </li>
            <li class="single-list-item">
                <a href="/items">
                    <i class="livicon-evo icon-sidemenu" data-options="name:hammer.svg; repeat: loop; eventOn: parent; style: solid;"></i>
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
                        <i class="livicon-evo icon-sidemenu" data-options="name:line-chart.svg; repeat: loop; eventOn: parent; style: solid;"></i>
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
                        <i class="livicon-evo icon-sidemenu" data-options="name:settings.svg; repeat: loop; eventOn: parent; style: solid;"></i>
                        Settings
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