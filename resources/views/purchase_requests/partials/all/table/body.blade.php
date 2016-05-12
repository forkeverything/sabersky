<template v-for="purchaseRequest in purchaseRequests">
    <tr class="row-single-pr">
        <td class="no-wrap col-number fit-to-content">
            <a :href="'/purchase_requests/' + purchaseRequest.id"
               alt="Link to single PR"
               class="underline"
            >
                #@{{ purchaseRequest.number }}
            </a>
                                                <span v-if="purchaseRequest.urgent"
                                                      class="badge-urgent">
                                                    <i class="fa fa-warning"></i>
                                                </span>
        </td>
        <td class="col-project"><a :href="'/projects/' + purchaseRequest.project.id"
                                   alt="project link">@{{ purchaseRequest.project.name }}</a>
        </td>
        <td class="col-quantity content-center">@{{ purchaseRequest.quantity }}</td>
        <td class="col-item">
            <div class="item-sku"
                 v-if="purchaseRequest.item.sku && purchaseRequest.item.sku.length > 0">@{{ purchaseRequest.item.sku }}</div>
            <a class="link-item dotted" :href="'/items/' + purchaseRequest.item.id" alt="item link">
                                            <span class="item-brand"
                                                  v-if="purchaseRequest.item.brand.length > 0">@{{ purchaseRequest.item.brand }}</span>
                <span class="item-name">@{{ purchaseRequest.item.name }}</span>
            </a>
            <ul class="item-image-gallery list-unstyled list-inline"
                v-if="purchaseRequest.item.photos.length > 0">
                <li v-for="photo in purchaseRequest.item.photos">
                    <a :href="photo.path" rel="group" class="fancybox"><img
                                :src="photo.thumbnail_path"
                                alt="Purchase Request Item Photo"></a>
                </li>
            </ul>
                                        <span class="item-specification">
                                        <text-clipper :text="purchaseRequest.item.specification"></text-clipper></span>
        </td>
        <td class="no-wrap">
            <span class="pr-due">@{{ purchaseRequest.due | easyDate }}</span>
        </td>
        <td>
            <span class="pr-requested">@{{ purchaseRequest.created_at | diffHuman }}</span>
        </td>
        <td>
            <span class="pr-requester">@{{ purchaseRequest.user.name | capitalize }}</span>
        </td>
    </tr>
</template>