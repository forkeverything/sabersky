<h5>Purchase</h5>
<table class="line-items table-responsive">
    <!-- PO Single - Items Table -->
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>SKU</th>
            <th>Description</th>
            <th>Required Date</th>
            <th>Qty</th>
            <th>Unit Price</th>
            <th>Total</th>
        <tr>
        </tr>
        </thead>
        <tbody>
        @foreach($purchaseOrder->lineItems as $index => $lineItem)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>@if($lineItem->purchaseRequest->item->sku){{ $lineItem->purchaseRequest->item->sku }}@else-@endif</td>
                <td>
                    <span class="item-brand-name">
                        @if($lineItem->purchaseRequest->item->brand)<span class="brand">{{ $lineItem->purchaseRequest->item->brand }}</span> - @endif
                        <span class="name">{{ $lineItem->purchaseRequest->item->name }}</span>
                    </span>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</table>