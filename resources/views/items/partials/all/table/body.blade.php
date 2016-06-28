<template v-for="item in items">
    <tr class="item-row" v-if="item && item.id">
        <td class="col-thumbnail">
            <div class="item-thumbnail">
                <img :src="item.photos[0].thumbnail_path"
                     alt="Item Thumbnail"
                     v-if="item.photos.length > 0"
                >
                <img src="/images/icons/thumbnail-item.svg"
                     alt="Item Thumbnail Placeholder"
                     v-else
                >
            </div>
        </td>
        <td class="col-details">
            <a class="link-item dotted" :href="'/items/' + item.id" alt="single item link">
                <div class="item-brand" v-if="item.brand"><span>@{{ item.brand }}</span></div>
                <div class="item-name"><span>@{{ item.name }}</span></div>
            </a>
                                    <span class="item-specification">
                                        <text-clipper :text="item.specification"></text-clipper></span>
        </td>
        <td class="col-sku no-wrap">
            <a :href="'/items/' + item.id" alt="single item link" v-if="item.sku">
                <span class="item-sku">@{{ item.sku }}</span>
            </a>
            <span v-else>-</span>
        </td>
        <td class="no-wrap content-center">
            <span>@{{ getItemProjects(item).length }}</span>
        </td>
    </tr>
</template>