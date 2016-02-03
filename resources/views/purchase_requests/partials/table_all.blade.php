<div class="table-responsive">
    <table class="table table-hover table-purchase-requests">
        <thead>
        <tr>
            @include('purchase_requests.partials.table_headers')
        </tr>
        </thead>
        <tbody>
        @foreach($purchaseRequests as $purchaseRequest)
            <tr data-href="{{ route('singlePurchaseRequest', $purchaseRequest->id) }}" class="@if($purchaseRequest->urgent) purchase-request-urgent @endif {{ strtolower($purchaseRequest->state) }}">
                <td>{{ $purchaseRequest->due->format('d M Y') }}</td>
                <td>{{ $purchaseRequest->project->name }}</td>
                <td>{{ $purchaseRequest->item->name }}</td>
                <td>{{ str_limit($purchaseRequest->item->specification, 45, ' ...') }}</td>
                <td>{{ $purchaseRequest->quantity }}</td>
                <td>{{ $purchaseRequest->user->name }}</td>
                <td>{{ $purchaseRequest->created_at->diffForHumans() }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>