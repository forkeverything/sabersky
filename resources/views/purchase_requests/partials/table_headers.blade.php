@foreach($tableHeadings as $query => $heading)
    @if($query == 'specification')
        <th>
            Specification
        </th>
    @else
        <th>
            @if($sort == $query && $order == 'desc')
                <a href="{{ route('showAllPurchaseRequests', ['filter' => $filter, 'sort' => $sort, 'order' => 'asc', 'urgent' => $urgent]) }}">
                    <i class="fa fa-caret-down fa-btn"></i>
                    {{ $heading }}
                </a>
            @elseif ($sort == $query)
                <a href="{{ route('showAllPurchaseRequests', ['filter' => $filter, 'sort' => $sort, 'order' => 'desc', 'urgent' => $urgent]) }}">
                    <i class="fa fa-caret-up fa-btn"></i>
                    {{ $heading }}
                </a>
            @else
                <a href="{{ route('showAllPurchaseRequests', ['filter' => $filter, 'sort' => $query, 'order' => 'desc', 'urgent' => $urgent]) }}">
                    {{ $heading }}
                </a>
            @endif
        </th>
    @endif
@endforeach

