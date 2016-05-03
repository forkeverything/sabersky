Vue.component('vendor-connection', {
    name: 'vendorConnection',
    template: '<span v-if="vendor.linked_company" class="vendor-connection {{ vendor.linked_company.connection }}">{{ vendor.linked_company.connection }}</span>',
    props: ['vendor']
});