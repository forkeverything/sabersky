<template v-for="purchaseRequest in purchaseRequests">
    <tr class="row-single-pr"
        :class="{
            'is-selected': alreadySelectedPR(purchaseRequest)
        }"
    >

        <!-- Checkbox -->
        <td class="col-checkbox fit-to-content">
            <div class="checkbox styled">
                <label v-if="purchaseRequest.state === 'open'">
                    <i class="fa fa-check-square-o checked" v-if="alreadySelectedPR(purchaseRequest)"></i>
                    <i class="fa fa-square-o empty" v-else></i>
                    <input class="clickable hidden"
                           type="checkbox"
                    @change="selectPR(purchaseRequest)"
                    :checked="alreadySelectedPR(purchaseRequest)"
                    >
                </label>
                <label v-else>
                    <i class="fa fa-square-o empty disabled"></i>
                </label>
            </div>
        </td>

        <!-- Number -->
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

        <!-- Requested -->
        <td class="fit-to-content">
            <span class="pr-requested">@{{ purchaseRequest.created_at | diffHuman }}</span>
        </td>

        <!-- By -->
        <td>
            <span class="pr-requester">@{{ purchaseRequest.user.name | capitalize }}</span>
        </td>

        <!-- Project -->
        <td class="col-project">
            <a :href="'/projects/' + purchaseRequest.project.id"
               alt="project link"
            >
                @{{ purchaseRequest.project.name }}
            </a>
        </td>

        <!-- Quantity -->
        <td class="col-quantity content-center">@{{ purchaseRequest.quantity }}</td>

        <!-- Item -->
        <td class="col-item">
            <span class="item-sku display-block"
                  v-if="purchaseRequest.item.sku && purchaseRequest.item.sku.length > 0"
            >
                @{{ purchaseRequest.item.sku }}
            </span>
            <a class="link-item dotted"
               :href="'/items/' + purchaseRequest.item.id"
               alt="item link"
            >
                <span class="item-brand"
                      v-if="purchaseRequest.item.brand.length > 0"
                >
                    @{{ purchaseRequest.item.brand }}
                </span>
                <span class="item-name">@{{ purchaseRequest.item.name }}</span>
            </a>
            <ul class="item-image-gallery list-unstyled list-inline" v-if="purchaseRequest.item.photos.length > 0">
                <li v-for="photo in purchaseRequest.item.photos">
                    <a :href="photo.path" rel="group" class="fancybox">
                        <img :src="photo.thumbnail_path" alt="Purchase Request Item Photo">
                    </a>
                </li>
            </ul>
            <span class="item-specification">
                <text-clipper :text="purchaseRequest.item.specification"></text-clipper>
            </span>
        </td>

        <!-- Due -->
        <td class="no-wrap">
            <span class="pr-due">@{{ purchaseRequest.due | easyDate }}</span>
        </td>

        <!-- State -->
        <td class="col-state"
            :class="{
                'success': purchaseRequest.state === 'open',
                'disabled': purchaseRequest.state === 'fulfilled',
                'danger': purchaseRequest.state === 'cancelled'
            }">
                  @{{ purchaseRequest.state }}
        </td>

    </tr>
</template>