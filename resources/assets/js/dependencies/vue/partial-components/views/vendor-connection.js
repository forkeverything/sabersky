Vue.component('vendor-connection', {
    name: 'vendorConnection',
    template: '<span class="vendor-connection {{ vendor.linked_company.connection }}">{{ vendor.linked_company.connection }}</span>',
    props: ['vendor']
});