<th>
    @if($field == 'due_date' && $order == 'desc')
        <a href="/purchase_requests?sort=due_date&order=asc">
            <i class="fa fa-caret-down fa-btn"></i>
            Due Date
        </a>
    @elseif ($field == 'due_date')
        <a href="/purchase_requests?sort=due_date&order=desc">
            <i class="fa fa-caret-up fa-btn"></i>
            Due Date
        </a>
    @else
        <a href="/purchase_requests?sort=due_date&order=desc">
            Due Date
        </a>
    @endif
</th>
<th>
    @if($field == 'project' && $order == 'desc')
        <a href="/purchase_requests?sort=project&order=asc">
            <i class="fa fa-caret-down fa-btn"></i>
            Project
        </a>
    @elseif ($field == 'project')
        <a href="/purchase_requests?sort=project&order=desc">
            <i class="fa fa-caret-up fa-btn"></i>
            Project
        </a>
    @else
        <a href="/purchase_requests?sort=project&order=desc">
            Project
        </a>
    @endif
</th>
<th>
    @if($field == 'item' && $order == 'desc')
        <a href="/purchase_requests?sort=item&order=asc">
            <i class="fa fa-caret-down fa-btn"></i>
            Item
        </a>
    @elseif ($field == 'item')
        <a href="/purchase_requests?sort=item&order=desc">
            <i class="fa fa-caret-up fa-btn"></i>
            Item
        </a>
    @else
        <a href="/purchase_requests?sort=item&order=desc">
            Item
        </a>
    @endif
</th>
<th>Specification</th>
<th>
    @if($field == 'quantity' && $order == 'desc')
        <a href="/purchase_requests?sort=quantity&order=asc">
            <i class="fa fa-caret-down fa-btn"></i>
            Quantity
        </a>
    @elseif ($field == 'quantity')
        <a href="/purchase_requests?sort=quantity&order=desc">
            <i class="fa fa-caret-up fa-btn"></i>
            Quantity
        </a>
    @else
        <a href="/purchase_requests?sort=quantity&order=desc">
            Quantity
        </a>
    @endif
</th>
<th>
    @if($field == 'user' && $order == 'desc')
        <a href="/purchase_requests?sort=user&order=asc">
            <i class="fa fa-caret-down fa-btn"></i>
            Requested by
        </a>
    @elseif ($field == 'user')
        <a href="/purchase_requests?sort=user&order=desc">
            <i class="fa fa-caret-up fa-btn"></i>
            Requested by
        </a>
    @else
        <a href="/purchase_requests?sort=user&order=desc">
            Requested by
        </a>
    @endif
</th>
<th>
    @if($field != 'due_date' && $field != 'project' && $field != 'item' && $field != 'quantity' && $field != 'user' && $order == 'desc')
        <a href="/purchase_requests?sort=time_requested&order=asc">
            <i class="fa fa-caret-down fa-btn"></i>
            Requested
        </a>
    @elseif ($field != 'due_date' && $field != 'project' && $field != 'item' && $field != 'quantity' && $field != 'user')
        <a href="/purchase_requests?sort=time_requested&order=desc">
            <i class="fa fa-caret-up fa-btn"></i>
            Requested
        </a>
    @else
        <a href="/purchase_requests?sort=time_requested&order=asc">
            Requested
        </a>
    @endif
</th>
