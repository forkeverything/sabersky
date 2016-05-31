Vue.component('product-category-selecter', {
    name: 'ProductCategorySelecter',
    template: '<label>Category</label>' +
    '<select-type :name.sync="name" :create="false"></select-type>',
    data: function() {
        return {

        };
    },
    props: ['name'],
    computed: {

    },
    methods: {

    },
    events: {

    },
    ready: function() {

    }
});