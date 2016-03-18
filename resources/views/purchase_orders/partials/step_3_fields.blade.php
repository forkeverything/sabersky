<section>
    <h2>Step 3: Add Items</h2>
    <div class="row">
        <div class="col-sm-4">
            <a href="{{ route('addLineItem') }}">
                <button class="btn-outline-blue button-add-line-item" type="button">Add Item</button>
            </a>
        </div>
    </div>
    @if($existingPO->lineItems()->first())
        <div class="po-line-items">
            <div class="table-responsive">
                <!-- Line Items Table -->
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Item</th>
                        <th>Specification</th>
                        <th>Delivery</th>
                        <th>Unit Price</th>
                        <th>QTY</th>
                        <th>Subtotal</th>
                        <th>Payable</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($existingPO->lineItems as $lineItem)
                        <tr>
                            <td>
                                {{ $lineItem->purchaseRequest->item->name }}
                            </td>
                            <td>
                                {{ $lineItem->purchaseRequest->item->specification }}
                            </td>
                            <td>
                                {{ $lineItem->delivery->format('d/m/Y') }}
                            </td>
                            <td>
                                {{ number_format($lineItem->price) }}
                            </td>
                            <td>
                                {{ $lineItem->quantity }}
                            </td>
                            <td>
                                {{ number_format($lineItem->quantity * $lineItem->price) }}
                            </td>
                            <td>
                                {{ $lineItem->payable->format('d/m/Y') }}
                            </td>
                            <td>
                                <button type="button"
                                        class="line-item-remove btn"
                                        @click="removeLineItem({{ $lineItem->id }})"
                                >&times;</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <span class="po-order-total"><span class="border">Order Total: {{ number_format($existingPO->total) . ' Rp' }}</span></span>
        </div>
        <button class="btn btn-solid-green">Submit Purchase Order</button>
    @else
        <p class="text-center">No Purchase Requests have been added yet.</p>
    @endif
</section>