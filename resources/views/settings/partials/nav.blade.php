<ul class="nav nav-tabs" role="tablist" v-autofit-tabs>
    <li class="clickable @if($page === 'company') active @endif" role="presentation">
        <a href="/settings/company">Company</a>
    </li>
    <li class="clickable @if($page === 'permissions') active @endif" role="presentation">
        <a href="/settings/permissions">Permissions</a>
    </li>
    <li class="clickable @if($page === 'rules') active @endif" role="presentation">
        <a href="/settings/rules">Rules</a>
    </li>
</ul>