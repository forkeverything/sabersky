Vue.component('address', {
    name: 'singleAddress',
    template: '<div class="address" v-if="address">' +
    '<span v-if="address.contact_person" class="contact_person display-block">{{ address.contact_person }}</span>' +
    '<span v-if="company" class="company_name display-block">{{ company.name }}</span>' +
    '<span class="address_1 display-block">{{ address.address_1 }}</span>' +
    '<span v-if="address.address_2" class="address_2 display-block">{{ address.address_2 }}</span>' +
    '<span class="city">{{ address.city }}</span>,' +
    '<span class="zip">{{ address.zip }}</span>' +
    '<div class="state-country display-block">' +
    '<span class="state">{{ address.state }}</span>,' +
    '<span class="country">{{ address.country }}</span><br>' +
    '<span class="phone"><abbr title="Phone">P:</abbr> {{ address.phone }}</span>' +
    '</div>' +
    '</div>',
    props: ['address', 'company']
});