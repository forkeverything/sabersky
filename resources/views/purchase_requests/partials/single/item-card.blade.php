<h4 class="card-title">Item</h4>
<div class="top">
    <div class="main-photo">
        @if($mainPhoto = $purchaseRequest->item->photos->first())
            <a href="{{ $mainPhoto->path }}" class="fancybox image-item-main" rel="group"><img
                        src="{{ $mainPhoto->thumbnail_path }}" alt="Item Main Photo"></a>
        @else
            <div class="placeholder">
                <i class="fa fa-image"></i>
            </div>
        @endif
    </div>
    <div class="details-item">
        @if($sku = $purchaseRequest->item->sku)
            <span class="sku">{{ $sku }}</span>
        @endif
        <a class="dotted item-link" href="{{ route('getSingleItem', $purchaseRequest->item->id) }}">
            <div class="brand"><span>{{ $purchaseRequest->item->brand }}</span></div>
            <div class="name"><span>{{ $purchaseRequest->item->name }}</span></div>
        </a>
    </div>
</div>
<p class="specification">{{ $purchaseRequest->item->specification }}</p>
@if($purchaseRequest->item->photos->count() > 1)
    <div class="item-images">
        <h5>Photos</h5>
        <ul class="image-gallery list-unstyled list-inline">
            @foreach($purchaseRequest->item->photos as $photo)
                <li class="single-item-image"><a href="{{ $photo->path }}" class="fancybox"
                                                 rel="group"><img
                                src="{{ $photo->thumbnail_path }}" alt="item image"></a></li>
            @endforeach
        </ul>
    </div>
@endif
@if($purchaseRequest->item->lineItems->count() > 0)
    <div class="order-history">
        <h5>Recent Orders</h5>
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>PO</th>
                <th>Vendor</th>
                <th>Quantity</th>
            <tr>
            </thead>
            <tbody>
            @foreach($purchaseRequest->item->lineItems->take(5) as $lineItem)
                <tr>
                    <td>{{ $lineItem->purchaseOrder->number }}</td>
                    <td>{{ $lineItem->purchaseOrder->vendor->name }}</td>
                    <td>{{ $lineItem->quantity }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif