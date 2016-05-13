Vue.component('purchase-requests-make', {
    name: 'makePurchaseRequest',
    el: function () {
        return '#purchase-requests-add';
    },
    data: function () {
        return {
            pageReady: false,
            ajaxReady: true,
            projectID: '',
            itemID: '',
            quantity: '',
            due: '',
            urgent: ''
        };
    },
    methods: {
        submitMakePRForm: function () {
            var self = this;


            // Send Req. via Ajax
            vueClearValidationErrors(self);
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/purchase_requests/make',
                method: 'POST',
                data: {
                    'project_id': self.projectID,
                    'item_id': self.itemID,
                    'quantity': self.quantity,
                    'due': self.due,
                    'urgent': (self.urgent) ? 1 : 0
                },
                success: function (data) {
                    // success
                    console.log(data);
                    console.log('success!');
                    flashNotifyNextRequest('success', 'Made a new Purchase Request');
                    window.location.href = "/purchase_requests";
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

    },
    ready: function () {
        var self = this;

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
                        '           <span class="item-sku">' + sku + '</span>' +
                        '           <span class="item-brand">' + brand + '</span>' +
                        '           <span class="item-name">' + escape(item.name) + '</span>' +
                        '       </div>' +
                        '</div>';
                },
                item: function (item, escape) {

                    var sku = (item.sku) ? escape(item.sku) : '';
                    var brand = (item.brand) ? escape(item.brand) + ' - ' : '';
                    var image = (item.photos[0]) ? ('<img src="' + escape(item.photos[0].thumbnail_path) + '">') : '<i class="fa fa-image"></i>';
                    var imageGallery =  '';
                    if(item.photos.length > 0) {
                        imageGallery += '<ul class="item-images list-unstyled">';
                        for(var i = 0 ; i < item.photos.length; i++) {
                            imageGallery += '<li class="item-select-image"><a class="fancybox" rel="group" href="' + escape(item.photos[i].path) + '"><img src="' + escape(item.photos[i].thumbnail_path) + '" alt="" /></a></li>'
                        }
                        imageGallery += '</ul>';
                    }

                    return '<div class="item-selected">' +
                        '       <div class="item-thumbnail">' +
                                    image +
                        '       </div>' +
                        '       <div class="details">' +
                        '           <span class="item-sku">' + sku + '</span>' +
                        '           <span class="item-brand">' + brand + '</span>' +
                        '           <span class="item-name">' + escape(item.name) + '</span>' +
                        '           <span class="item-specification">' + escape(item.specification) + '</span>' +
                        '       </div>' +
                                imageGallery +
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
                self.itemID = value;
            }
    });

        self.$nextTick(function () {
            self.pageReady = true;
        });
    }
});

