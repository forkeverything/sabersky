Vue.component('modal-select-address', {
    name: 'modalSelectAddress',
    template: '<div><button type="button" v-show="! selected" class="btn btn-small button-select-address btn-outline-blue" @click="showModal">Select Address</button>' +
    '<div class="modal-select-address modal-overlay" v-show="visible" @click="hideModal">' +
    '<div class="modal-body" @click.stop="">' +
    '<button type="button" @click="hideModal" class="btn button-hide-modal"><i class="fa fa-close"></i></button>' +
    '<h2>Select an Address</h2>' +
    '<ul class="list-unstyled list-address" v-if="addresses.length > 0">' +
    '<li class="single-address clickable" v-for="address in addresses" @click="select(address)">' +
    '<address :address="address"></address>' +
    '</div>' +
    '</li>' +
    '</ul>' +
    '<em v-if="addresses.length == 0">No Addresses found, add an address to a Vendor to select it here.</em>' +
    '</div>' +
    '</div>' +
    '<div class="single-address clickable selected" v-show="selected">' +
    '<div class="change-overlay" @click="remove">' +
    '<i class="fa fa-close"></i>' +
    '<h3>Remove</h3>' +
    '</div>' +
    '<address :address="selected"></address>' +
    '</div>' +
    '</div>'+
    '</div>',
    data: function () {
        return {
            visible: false
        };
    },
    props: ['selected', 'addresses'],
    computed: {},
    methods: {
        showModal: function () {
            this.visible = true;
        },
        hideModal: function () {
            this.visible = false;
        },
        select: function (address) {
            this.selected = address;
            this.hideModal();
        },
        remove: function () {
            this.selected = '';
        }
    },
    events: {},
    ready: function () {

    }
});