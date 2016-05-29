<h4 class="card-title">Project</h4>
<div class="name text-center">
    <a class="dotted" href="{{ route('singleProject', $purchaseRequest->project->id) }}">
        {{ $purchaseRequest->project->name }}
    </a>
</div>
<div class="location text-center">{{ $purchaseRequest->project->location }}</div>
<hr>
<div class="started text-center">
    <h3>Start Date</h3>
    {{ $purchaseRequest->project->created_at->format('d M Y') }}
</div>
<hr>
<div class="team text-center">
    <h3>Team Members</h3>
    {{ $purchaseRequest->project->teamMembers->count() }}
</div>
<hr>
<div class="purchase-requests">
    <h3>Purchase Requests</h3>
    <!-- Project PR Details Table -->
    <table class="table">
        <tbody>
        <tr class="open">
            <td>Open</td>
            <td>{{ $purchaseRequest->project->purchaseRequests->where('state', 'open')->count() }}</td>
        </tr>
        <tr class="completed">
            <td>Completed</td>
            <td>{{ $purchaseRequest->project->purchaseRequests->where('quantity' , 0)->count() }}</td>
        </tr>
        <tr class="cancelled">
            <td>Cancelled</td>
            <td>{{ $purchaseRequest->project->purchaseRequests->where('state', 'cancelled')->count() }}</td>
        </tr>
        </tbody>
    </table>
</div>