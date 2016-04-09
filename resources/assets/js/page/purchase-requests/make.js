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
        submitMakePRForm: function() {
            var self = this;

            // Create new FormData Instance
            var fd = new FormData();

            // Attach our previously uploaded files to data
            _.forEach(self.uploadedFiles, function (file) {
                fd.append('item_photos[]', file);
            });

            // Append our other data
            fd.append('project_id', self.projectID);
            if(self.selectedItem) fd.append('item_id', self.selectedItem.id);
            fd.append('name', self.newItemName);
            fd.append('specification', self.newItemSpecification);
            fd.append('quantity', self.quantity);
            fd.append('due', self.due);
            
            // Send Req. via Ajax
            vueClearValidationErrors(self);
            if(!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/purchase_requests/make',
                method: 'POST',
                data: fd,
                contentType: false,
                processData: false,
                success: function(data) {
                   // success
                    console.log('success!');
                    console.log(data);
                   self.ajaxReady = true;
                },
                error: function(response) {
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

        // Initialize using selectize.js
        var newItemNameSelecter = $('#select-new-item-name').selectize({
            create: true,
            sortField: 'text',
            placeholder: 'Select or enter a new Item',
            createFilter: function (input) {
                // Filter that makes sure value names added are unique
                input = input.toLowerCase();
                var array = $.map(newItemNameSelecter.options, function (value) {
                    return [value];
                });
                var unmatched = true;
                _.forEach(array, function (option) {
                    if ((option.text).toLowerCase() === input) {
                        unmatched = false;
                    }
                });
                return unmatched;
            },
            onChange: function(value) {
                // When we select / enter a new value - enter it into our data
                self.newItemName = value;
            }
        })[0].selectize;

            // Initialize File Uploads
            $('#item-photos-upload').fileupload({
                autoUpload: false
            }).on('fileuploadadd', function (e, data) {
                _.forEach(data.files, function(file) {
                    self.uploadedFiles.push(file);
                });
            });

        // $("#form-make-purchase-request").submit(function(e) {
        //     if (uploadedFiles.length > 0) {
        //         e.preventDefault();
        //         $('#item-photos-upload').fileupload('send', {files: uploadedFiles})
        //             .complete(function (result, textStatus, jqXHR) {
        //                 // window.location='back to view-page after submit?'
        //             });
        //     } else {
        //         // plain default submit
        //     }
        // });
        // });

        self.$nextTick(function () {
            self.pageReady = true;
        });
    }
});

