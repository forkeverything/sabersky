Vue.component('product-category-selecter', {
    name: 'ProductCategorySelecter',
    template: '<div class="product-category-selecter">' +
    '<select class="product-category-select" v-el:select>' +
    '<option></option>' +
    '</select>' +
    '</div>',
    props: ['value'],
    ready: function() {
        var self = this,
            $select,
            select;

        $select = $(self.$els.select).selectize({
            valueField: 'id',
            searchField: 'label',
            labelField: 'label',
            create: false,
            placeholder: 'Category',
            onChange: function (value) {
                self.value = value;
            }
        });

        select = $select[0].selectize;

        select.load(function(callback) {
            $.get('/product_categories', function (data) {
                callback(data);
            });
        });
    }
});