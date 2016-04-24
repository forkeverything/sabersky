Vue.component('add-address-modal', {
    name: 'addAddressModal',
    template: '<button type="button"' +
    '                  class="btn btn-add-address btn-outline-green"' +
    '                  @click="showModal"' +
    '                  >' +
    '                  <i class="fa fa-plus"></i> Address' +
    '          </button>' +
    '          <div class="modal-address-add modal-form" v-show="visible" @click="hideModal">' +
    '               <form class="form-item-add main-form" v-show="loaded" @click.stop="">' +
    '                   <button type="button" @click="hideModal" class="btn button-hide-modal"><i class="fa fa-close"></i></button>' +
    '                   <form-errors></form-errors>' +
    '                   <h3>Add Address</h3>' +
    '                   <div class="form-group">' +
    '                       <label class="required">Address</label>' +
    '                       <input class="form-control" type="text" v-model="address1">' +
    '                   </div>' +
    '                   <div class="form-group">' +
    '                       <label>Address 2</label>' +
    '                       <input class="form-control" type="text" v-model="address2">' +
    '                   </div>' +
    '<div class="row">' +
    '<div class="col-sm-6">' +
    '                   <div class="form-group">' +
    '                       <label class="required">Country</label>' +
    '                       <input class="form-control" type="text" v-model="country">' +
    '                   </div>' +
    '</div>' +
    '<div class="col-sm-6">' +
    '                   <div class="form-group">' +
    '                       <label class="required">State</label>' +
    '                       <input class="form-control" type="text" v-model="state">' +
    '                   </div>' +
    '</div>' +
    '</div>' +
    '<div class="row">' +
    '<div class="col-sm-6">' +
    '                   <div class="form-group">' +
    '                       <label class="required">Zip</label>' +
    '                       <input class="form-control" type="text" v-model="zip">' +
    '                   </div>' +
    '</div>' +
    '<div class="col-sm-6">' +
    '                   <div class="form-group">' +
    '                       <label class="required">Phone</label>' +
    '                       <input class="form-control" type="text" v-model="phone">' +
    '                   </div>' +
    '</div>' +
    '</div>' +
    '               </form>' +
    '          </div>',
    data: function() {
        return {
            visible: false,
            loaded: false,
            address1: '',
            address2: '',
            state: '',
            country: '',
            zip: '',
            phone: ''
        };
    },
    props: ['model-id', 'model-type'],
    computed: {

    },
    methods: {
        showModal: function() {
            this.visible = true;
        },
        hideModal: function() {
            this.visible = false;
        }
    },
    events: {

    },
    ready: function() {
        var self = this;

        self.loaded = true;
    }
});