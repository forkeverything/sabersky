Vue.component('single-pr-modal', {
    name: 'singlePurchaseRequestModal',
    template: '<div class="modal-overlay single-pr" tabindex="-1" role="dialog" aria-labelledby="singlePRModal" aria-hidden="true" v-show="visible" @click="hideModal">' +
    '               <div class="modal-body" @click.stop="">'+
    '                   <modal-close-button></modal-close-button>' +
    '                       <h3>Purchase Request #{{ purchaseRequest.number }} <span'+
    '                           class="badge-state {{ purchaseRequest.state }}">{{ purchaseRequest.state }}</span>' +
    '                       </h3>' +
    '                       <div class="pr">' +
    '                           <div class="request-info">' +
    '                               <span class="requested">Requested {{ purchaseRequest.created_at | diffHuman }}</span>' +
    '                               <span class="requester">{{ purchaseRequest.user.name }}</span>' +
    '                           </div>' +
    '                           <div class="due">' +
    '                               <h5>Due Date</h5>' +
    '                               <span class="date">{{ purchaseRequest.due | easyDate }}</span>' +
    '                           </div>' +
    '                           <div class="quantity">' +
    '                               <h5>Quantity</h5>' +
    '                               <span class="number">{{ purchaseRequest.quantity }}</span>' +
    '                           </div>' +
    '                       </div>' +
    '                       <div class="item">' +
    '                           <h5>Item</h5>' +
    '                           <div class="main-photo">' +
    '                               <a v-if="purchaseRequest.item.photos.length > 0" :href="purchaseRequest.item.photos[0].path" class="fancybox image-item-main" rel="group">' +
    '                                   <img :src="purchaseRequest.item.photos[0].thumbnail_path" alt="Item Main Photo">' +
    '                               </a>' +
    '                               <div class="placeholder" v-else>'+
    '                                   <i class="fa fa-image"></i>'+
    '                               </div>' +
    '                           </div>' +
    '                           <div class="details">' +
    '                               <span class="sku display-block" v-if="purchaseRequest.item.sku">{{ purchaseRequest.item.sku }}</span>' +
    '                               <span class="brand" v-if="purchaseRequest.item.brand">{{ purchaseRequest.item.brand }} - </span>' +
    '                               <span class="name">{{ purchaseRequest.item.name }}</span>' +
    '                               <p class="specification">{{ purchaseRequest.item.specification }} </p>' +
    '                               <div class="item-images" v-if="purchaseRequest.item.photos.length > 0">' +
    '                                   <ul class="image-gallery list-unstyled list-inline">' +
    '                                       <li class="single-item-image" v-for="photo in purchaseRequest.item.photos">' +
    '                                           <a :href="photo.path" v-fancybox rel="group">' +
    '                                               <img :src="photo.thumbnail_path" alt="item image">' +
    '                                           </a>' +
    '                                       </li>' +
    '                                   </ul>' +
    '                               </div>' +
    '                          </div>' +
    '                      </div>'+
    '             </div>' +
    '       </div>',
    data: function() {
        return {
            purchaseRequest: {},
            visible: false
        }
    },
    props: [],
    computed: {

    },
    methods: {
        hideModal: function() {
            this.visible = false;
        }
    },
    events: {
        'modal-show-single-pr': function(purchaseRequest) {
            this.purchaseRequest = purchaseRequest;
            this.$nextTick(function () {
                this.visible = true;
            });
        },
        'click-close-modal': function() {
            this.hideModal();
        }
    },
    ready: function() {

    }
});

