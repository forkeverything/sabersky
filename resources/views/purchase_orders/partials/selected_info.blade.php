@if($existingPO)
    <h2>Selected Details</h2>
    <div class="po-selected-info">
        <div class="header">
            @if($existingPO->project_id)
                <span class="project-name capitalize">{{ $existingPO->project->name }}</span>
            @endif
            <a href="{{ route('cancelUnsubmittedPO') }}"><button class="btn btn-danger">Cancel</button></a>
        </div>
        @if($existingPO->vendor_id)
            <hr>
            <strong>Vendor: </strong>{{ $existingPO->vendor->name }}
            <br>
            <strong>Phone Number: </strong>{{ $existingPO->vendor->phone }}
            <br>
            <strong>Bank: </strong>{{ $existingPO->vendor->bank_name }}
            <br>
            <strong>Account Name: </strong>{{ $existingPO->vendor->bank_account_name }}
            <br>
            <strong>Account No: </strong>{{ $existingPO->vendor->bank_account_number }}
        @endif
    </div>
@endif