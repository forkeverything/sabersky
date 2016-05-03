Vue.component('modal-select-address', {
    name: 'modalSelectAddress',
    template: '<button type="button" v-show="! selected" class="btn button-select-address btn-outline-blue" @click="showModal">Select Address</button>' +
    '<div class="modal-select-address modal-overlay" v-show="visible" @click="hideModal">' +
    '<div class="modal-body" @click.stop="">' +
    '<button type="button" @click="hideModal" class="btn button-hide-modal"><i class="fa fa-close"></i></button>' +
    '<h3>Select an Address</h3>' +
    '<ul class="list-unstyled list-address" v-if="addresses.length > 0">' +
    '<li class="single-address clickable" v-for="address in addresses" @click="select(address)">' +
    '<span class="contact_person display-block" v-if="address.contact_person">{{ address.contact_person }}</span>' +
    '<span class="address_1 display-block">{{ address.address_1 }}</span>' +
    '<span class="address_2 display-block" v-if="address.address_2">{{ address.address_2 }}</span>' +
    '<span class="city">{{ address.city }}</span>,' +
    '<div class="zip">{{ address.zip }}</div>' +
    '<div class="state-country display-block">' +
    '<span class="state">{{ address.state }}</span>,' +
    '<span class="country">{{ address.country }}</span><br>' +
    '<span class="phone"><abbr title="Phone">P:</abbr> {{ address.phone }}</span>' +
    '</div>' +
    '</li>' +
    '</ul>' +
    '<em v-else>No Addresses found, add an address to a Vendor to select it here.</em>' +
    '</div>' +
    '</div>' +
    '<div class="single-address clickable selected" v-show="selected">' +
    '<div class="change-overlay" @click="remove">' +
    '<i class="fa fa-close"></i>' +
    '<h3>Remove</h3>' +
    '</div>' +
    '<span class="contact_person display-block" v-if="selected.contact_person">{{ selected.contact_person }}</span>' +
    '<span class="address_1 display-block">{{ selected.address_1 }}</span>' +
    '<span class="address_2 display-block" v-if="selected.address_2">{{ selected.address_2 }}</span>' +
    '<span class="city">{{ selected.city }}</span>,' +
    '<span class="zip">{{ selected.zip }}</span>' +
    '<div class="state-country display-block">' +
    '<span class="state">{{ selected.state }}</span>,' +
    '<span class="country">{{ selected.country }}</span><br>' +
    '<span class="phone"><abbr title="Phone">P:</abbr> {{ selected.phone }}</span>' +
    '</div>' +
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