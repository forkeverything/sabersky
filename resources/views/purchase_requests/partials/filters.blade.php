<div class="purchase-request-filters">
    <ul class="list-unstyled list-inline">
        @foreach($filterStates as $state => $label)
            <li>
                <a href="{{ route('showAllPurchaseRequests', ['order' => $order, 'sort' => $sort, 'filter' => $state, 'urgent' => $urgent]) }}"
                   @if($filter == $state || ($state == 'open' && $filter !== 'completed' && $filter !== 'cancelled'))
                   class="active"
                        @endif
                >
                    {{ $label }}
                </a>
            </li>
        @endforeach
    </ul>
    <a
            @if($urgent)
            class="filter-urgent active"
            @else
            class="filter-urgent"
            @endif
            href="{{ route('showAllPurchaseRequests', ['order' => $order, 'sort' => $sort, 'filter' => $filter, 'urgent' => !$urgent]) }}">Urgent Only</a>
</div>