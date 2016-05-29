<p class="card-title">Item</p>
<hr>
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
            <div><span class="item-sku">{{ $sku }}</span></div>
        @endif
        <a class="dotted item-link" href="{{ route('getSingleItem', $purchaseRequest->item->id) }}">
            @if($purchaseRequest->item->brand)
            <span class="item-brand">{{ $purchaseRequest->item->brand }}</span> -
            @endif
            <span class="item-name">{{ $purchaseRequest->item->name }}</span>
        </a>
    </div>
</div>
<p class="specification">{{ $purchaseRequest->item->specification }}</p>
@if($purchaseRequest->item->photos->count() > 1)
    <hr>
    <div class="item-images">
        <h3>Photos</h3>
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
    <hr>
    <div class="order-history">
        <h3>Recent Orders</h3>
        <div class="table-responsive">
            <table class="table table-hover table-standard">
                <thead>
                <tr>
                    <th class="padding-even">PO</th>
                    <th>Vendor</th>
                    <th class="padding-even">Quantity</th>
                <tr>
                </thead>
                <tbody>
                @foreach($purchaseRequest->item->lineItems->take(5) as $lineItem)
                    <tr>
                        <td class="content-center padding-even"><a href="{{ route('singlePurchaseOrder', $lineItem->purchaseOrder->id) }}" alt="Single PO Link">#{{ $lineItem->purchaseOrder->number }}</a></td>
                        <td>{{ $lineItem->purchaseOrder->vendor->name }}</td>
                        <td class="content-center padding-even">{{ $lineItem->quantity }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif