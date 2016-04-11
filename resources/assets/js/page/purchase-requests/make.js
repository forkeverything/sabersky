Vue.component('purchase-requests-make', {
    name: 'makePurchaseRequest',
    el: function () {
        return '#purchase-requests-add';
    },
    data: function () {
        return {
            pageReady: false,
            existingItem: true,
            items: [],
            existingItemName: '',
            selectedItem: '',
            uploadedFiles: [],
            ajaxReady: true,
            projectID: '',
            newItemName: '',
            newItemSpecification: '',
            quantity: '',
            due: '',
            urgent: ''
        };
    },
    methods: {
        changeExistingItem: function (state) {
            this.clearSelectedExisting();
            this.existingItem = state;
        },
        selectItemName: function (name) {
            this.existingItemName = name;
        },
        selectItem: function (item) {
            this.selectedItem = item;
        },
        clearSelectedExisting: function () {
            this.selectedItem = '';
            this.existingItemName = '';
            $('#select-new-item-name')[0].selectize.clear();
            $('#field-new-item-specification').val('');
            $('.input-item-photos').fileinput('clear');
        },
        submitMakePRForm: function () {
            var self = this;

            // Create new FormData Instance
            var fd = new FormData();

            // Attach our previously uploaded files to data
            _.forEach(self.uploadedFiles, function (file) {
                fd.append('item_photos[]', file);
            });

            // Append our other data
            fd.append('project_id', self.projectID);
            if (self.selectedItem) fd.append('item_id', self.selectedItem.id);
            fd.append('name', self.newItemName);
            fd.append('specification', self.newItemSpecification);
            fd.append('quantity', self.quantity);
            fd.append('due', self.due);

            // Send Req. via Ajax
            vueClearValidationErrors(self);
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/purchase_requests/make',
                method: 'POST',
                data: fd,
                contentType: false,
                processData: false,
                success: function (data) {
                    // success
                    console.log('success!');
                    console.log(data);
                    self.ajaxReady = true;
                },
                error: function (response) {
                    console.log(response);

                    vueValidation(response, self);
                    self.ajaxReady = true;
                }
            });
        }
    },
    computed: {
        uniqueItemNames: function () {
            return _.uniqBy(this.items, 'name');
        },
        itemsWithName: function () {
            return _.filter(this.items, {'name': this.existingItemName});
        }
    },
    ready: function () {
        var self = this;
        $.ajax({
            url: '/api/items',
            method: 'GET',
            success: function (data) {
                self.items = data;
            }
        });


        $('#pr-item-selection').selectize({
            valueField: 'id',
            searchField: ['sku', 'brand', 'name'],
            create: false,
            placeholder: 'Search by SKU, Brand or Name',
            render: {
                option: function (item, escape) {

                    var sku = (item.sku) ? escape(item.sku) : '';
                    var brand = (item.brand) ? escape(item.brand) + ' - ' : '';
                    var image = (item.photos[0]) ? ('<img src="' + escape(item.photos[0].thumbnail_path) + '">') : '<i class="fa fa-image"></i>';

                    return '<div class="item-single-option">' +
                        '       <div class="item-thumbnail">' +
                                    image +
                        '       </div>' +
                        '       <div class="details">' +
                        '           <span class="brand">' + brand + '</span>' +
                        '           <span class="name">' + escape(item.name) + '</span>' +
                        '           <span class="sku">' + sku + '</span>' +
                        '       </div>' +
                        '</div>';
                },
                item: function (item, escape) {
                    return '<div>' +
                        '<span class="brand">' + escape(item.brand) + '</span>' +
                        '-' +
                        '<span class="name">' + escape(item.name) + '</span>' +
                        '</div>'
                }
            },
            load: function (query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: '/api/items/search/' + encodeURIComponent(query),
                    type: 'GET',
                    error: function () {
                        callback();
                    },
                    success: function (res) {
                        console.log(res);
                        callback(res);
                    }
                });
            },
            onChange: function (value) {
                console.log(value);     // Item ID
            }
        });

        self.$nextTick(function () {
            self.pageReady = true;
        });
    }
});

