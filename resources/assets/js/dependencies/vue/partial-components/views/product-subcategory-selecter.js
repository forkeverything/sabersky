Vue.component('product-subcategory-selecter', {
    name: 'productSubcategorySelecter',
    template: '<div class="product-subcategory-selecter">' +
    '<select class="product-subcategory-select" v-el:select>' +
    '<option></option>' +
    '</select>' +
    '</div>',
    props: ['value', 'category'],
    ready: function() {
        var self = this,
            xhr,
            $select,
            select;

        this.$watch('category', function(value) {
            if (!value.length) return;
            select.disable();
            select.clearOptions();
            select.load(function(callback) {
                xhr && xhr.abort();
                xhr = $.ajax({
                    url: '/product_categories/' + value + '/subcategories',
                    success: function(results) {
                        select.enable();
                        callback(results);
                    },
                    error: function() {
                        callback();
                    }
                })
            });
        });

        $select = $(self.$els.select).selectize({
            valueField: 'id',
            searchField: 'label',
            labelField: 'label',
            create: false,
            placeholder: 'Subcategory',
            onChange: function (value) {
                self.value = value;
            }
        });

        select = $select[0].selectize;

        select.disable();


    }
});