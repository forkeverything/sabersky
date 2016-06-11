<div id="dashboard-quick-links" class="card">
    <span class="card-title">Quick Links</span>
    <hr>
    <ul class="list-unstyled list-links">
        @can('project_manage')
            <li>
                <a href="/projects/start" alt="link to start project">Start Project</a>
            </li>
        @endcan

        @can('team_manage')
            <li>
                <a href="/team/add" alt="link to invite team member">Invite Team Member</a>
            </li>
        @endcan

        @can('vendor_manage')
            <li>
                <a href="/vendors/add" alt="link add vendor">Add Vendor</a>
            </li>
        @endcan

        @can('pr_make')
            <li>
                <a href="/purchase_requests/make" alt="link make request">Make Purchase Request</a>
            </li>
        @endcan

        @can('po_submit')
            <li>
                <a href="/purchase_orders/submit" alt="link submit order">Submit Purchase Order</a>
            </li>
        @endcan
    </ul>
</div>