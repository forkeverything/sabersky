Vue.component('address', {
    name: 'singleAddress',
    template: '<div class="address">' +
    '<div class="contact-person">' +
    '<h3 class="left">Contact Person</h3>' +
    '<span class="name">{{ address.contact_person }}</span>' +
    '</div>' +
    '<div class="phone">' +
    '<h3 class="left">Phone</h3>' +
    '<span class="phone">{{ address.phone }}</span>' +
    '</div>' +
    '<div class="address">' +
    '<h3 class="left">Address</h3>' +
    '<span class="address_1 block">{{ address.address_1 }}</span>' +
    '<span class="address_2 block" v-if="address.address_2">{{ address.address_2 }}</span>' +
    '<span class="city">{{ address.city }}</span>' +
    '<span class="zip">{{ address.zip }}</span>' +
    '<div class="state-country block">' +
    '<span class="state">{{ address.state }}</span>,' +
    '<span class="country">{{ address.country }}</span>' +
    '</div>' +
    '</div>' +
    '</div>',
    props: ['address', 'company']
});