<p class="card-title">Item</p>
<hr>
<div class="top">
    <div class="main-photo">
            <a v-if="purchaseRequest.item.photos.length > 0" :href="purchaseRequest.item.photos[0].path" class="fancybox image-item-main" rel="group"><img
                        :src="purchaseRequest.item.photos[0].thumbnail_path" alt="Item Main Photo"></a>
            <div class="placeholder" v-else>
                <i class="fa fa-image"></i>
            </div>
    </div>
    <div class="details-item">
            <div v-if="purchaseRequest.item.sku">
                <span class="item-sku">@{{ purchaseRequest.item.sku }}</span>
            </div>
        <a class="dotted item-link" :href="'/items/' + purchaseRequest.item.id">
            <span class="item-brand" v-if="purchaseRequest.item.brand">@{{ purchaseRequest.item.brand }}</span> -
            <span class="item-name">@{{ purchaseRequest.item.name }}</span>
        </a>
    </div>
</div>
<p class="specification">@{{ purchaseRequest.item.specification }}</p>
    <div class="item-images" v-if="purchaseRequest.item.photos.length > 1">
        <hr>
        <h3>Photos</h3>
        <ul class="image-gallery list-unstyled list-inline">
                <li class="single-item-image" v-for="photo in purchaseRequest.item.photos">
                    <a :href="photo.path" class="fancybox" rel="group">
                        <img :src="photo.thumbnail_path" alt="item image">
                    </a>
                </li>
        </ul>
    </div>

    <div class="order-history" v-if="purchaseRequest.item.line_items.length > 0">
        <hr>
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
                    <tr v-for="lineItem in lineItems">
                        <td class="content-center padding-even"><a :href="'/purchase_orders/' + lineItem.purchase_order.id" alt="Single PO Link">#@{{ lineItem.purchase_order.number }}</a></td>
                        <td>@{{ lineItem.purchase_order.vendor.name }}</td>
                        <td class="content-center padding-even">@{{ lineItem.quantity }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
