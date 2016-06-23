<ul id="settings-nav" class="list-unstyled hidden-sm hidden-sm">
    <li><a class="@if($page === 'company') active @endif" href="/settings/company">Company</a></li>
    <li><a class="@if($page === 'roles') active @endif" href="/settings/roles">Roles</a></li>
    <li><a class="@if($page === 'purchasing') active @endif" href="/settings/purchasing">Purchasing</a></li>
</ul>

<h4 class="visible-sm visible-xs">View</h4>
<settings-dropdown-nav :page="'{{ $page }}'"></settings-dropdown-nav>