Vue.component('items-all', {
    name: 'allItems',
    el: function() {
        return '#items-all';
    },
    data: function() {
        return {
            brands: [],
            items: [],
            visibleAddItemModal: false,
            itemsFilterDropdown: false,
            filterOptions: [
                {
                    value: 'brand',
                    label: 'Brand'
                },
                {
                    value: 'projects',
                    label: 'Projects'
                }
            ],
            filter: '',
            filterBrand: ''
        };
    },
    computed: {
        itemNames: function() {
            var names = [];
            _.forEach(this.items, function (item) {
                names.push(item.name);
            });
            return names;
        }
    },
    methods: {
        showAddItemModal: function() {
            this.visibleAddItemModal = true;
        }
    },
    events: {
        'added-new-item': function (item) {
            this.items.push(item);
        }
    },
    ready: function() {
        var self = this;
        $.ajax({
            url: '/api/items',
            method: 'GET',
            success: function(data) {
                self.items = data;
            },
            error: function(err) {
                console.log(err);
            }
        });

        $.ajax({
            url: '/api/items/brands',
            method: 'GET',
            success: function(data) {
               // success
               self.brands = _.map(data, function(brand) {
                   if(brand.brand) {
                       brand.value = brand.brand;
                       brand.label = strCapitalize(brand.brand);
                       return brand;
                   }
               });
            },
            error: function(response) {
                console.log(response);
            }
        });
    }
});