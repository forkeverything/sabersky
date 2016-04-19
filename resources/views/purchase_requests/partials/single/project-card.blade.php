<h4 class="card-title">Project</h4>
<div class="name"><a class="dotted"
                     href="{{ route('singleProject', $purchaseRequest->project->id) }}">{{ $purchaseRequest->project->name }}</a>
</div>
<span class="location">{{ $purchaseRequest->project->location }}</span>
<div class="started">
    <span class="card-subheading">Start Date</span>
    {{ $purchaseRequest->project->created_at->format('d M Y') }}
</div>
<div class="team">
    <span class="card-subheading">Team Members</span>
    {{ $purchaseRequest->project->teamMembers->count() }}
</div>

<div class="purchase-requests">
    <span class="card-subheading">Purchase Requests</span>
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