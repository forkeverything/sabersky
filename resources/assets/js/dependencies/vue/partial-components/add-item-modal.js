Vue.component('add-item-modal', {
    name: 'addItemModal',
    template: '<div class="modal-item-add" v-show="visible" @click="hideModal">' +
    '<form class="form-item-add" v-show="loaded" @click.stop="">' +
    '<button type="button" @click="hideModal" class="btn button-hide-add-item-modal"><i class="fa fa-close"></i></button>' +
    '<form-errors></form-errors>' +
    '<h3>Add New Item</h3>' +
    '   <div class="form-group">' +
    '       <label>SKU</label>' +
    '       <input class="form-control" type="text" v-model="sku">' +
    '   </div>' +
    '<div class="form-group brand-name-wrap">' +
    '<div class="brand-selection"><label>Brand</label><select-type :options="existingBrands" :name.sync="brand" placeholder="Select or Add New"></select-type></div>' +
    '<div class="enter-name"><label  class="required">Name</label><input class="form-control" type="text" v-model="name"></div>' +
    '</div>' +
    '   <div class="form-group">' +
    '       <label  class="required">Specification</label>' +
    '       <textarea class="form-control" v-model="specification" rows="5"></textarea>' +
    '   </div>' +
    '<div class="form-group">' +
    '<div class="item-photo-uploader">' +
    '<label>Photos</label>' +
    '<div class="dropzone-errors" v-show="fileErrors.length > 0">' +
    '<span class="error-heading">Could not add the following files</span>' +
    '<span class="button-clear" @click="clearErrors">clear</span>' +
    '<ul class="file-upload-errors">' +
    '<li v-for="error in fileErrors" track-by="$index">{{ error }}</li>' +
    '</ul>' +
    '</div>' +
    '<div class="item-photo-dropzone dropzone">' +
    '<div class="dz-message"><i class="fa fa-image"></i>' +
    'Click or drop images to upload' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '<div class="bottom children-right">' +
    '   <button type="button"' +
    '           class="btn btn-solid-green"' +
    '           @click.prevent="submitAddItemForm"' +
    '           :disabled="! canSubmitForm"' +
    '   >' +
    '       Save Item' +
    '   </button>' +
    '</div>' +
    '</form>' +
    '</div>',
    data: function () {
        return {
            ajaxReady: true,
            loaded: false,
            existingBrands: null,
            sku: '',
            brand: '',
            name: '',
            specification: '',
            uploadedFiles: [],
            fileErrors: [],
            dropzone: {}
        };
    },
    props: ['visible'],
    computed: {
        canSubmitForm: function () {
            return this.name.length > 0 && this.specification.length > 0;
        }
    },
    methods: {
        hideModal: function() {
            this.visible = false;
        },
        clearErrors: function () {
            this.fileErrors = []
        },
        submitAddItemForm: function () {
            var self = this;

            // Create new FormData Instance
            var fd = new FormData();

            // Attach our previously uploaded files to data
            _.forEach(self.uploadedFiles, function (file) {
                fd.append('item_photos[]', file);
            });

            // Append our other data
            fd.append('sku', self.sku);
            fd.append('brand', self.brand);
            fd.append('name', self.name);
            fd.append('specification', self.specification);

            // Send Req. via Ajax
            vueClearValidationErrors(self);
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/api/items',
                method: 'POST',
                data: fd,
                contentType: false,
                processData: false,
                success: function (data) {
                    // success
                    console.log('success!');
                    console.log(data);
                    self.ajaxReady = true;
                    self.clearFields(); // Clear selected fields
                    self.$dispatch('new-item', data);   // Send out event for parent component
                    self.visible = false;
                    flashNotify('success', 'Added new Item');
                },
                error: function (response) {
                    console.log(response);

                    vueValidation(response, self);
                    self.ajaxReady = true;
                }
            });
        },
        clearFields: function () {
            this.sku = '';
            this.brand = '';
            this.name = '';
            this.specification = '';
            this.uploadedFiles = '';
            this.fileErrors = [];
            this.dropzone.removeAllFiles();
        }
    },
    events: {
        'select-loaded': function () {
            this.loaded = true;
        }
    },
    ready: function () {
        var self = this;

        // Fetch Existing Items
        $.ajax({
            url: '/api/items/brands',
            method: 'GET',
            success: function (data) {
                // success
                self.existingBrands = _.map(data, 'brand');
            },
            error: function (response) {
                console.log(response);
                console.log('Could not fetch existing items');
            }
        });

        // File Upload
        var dzMaxFileSize = 5 * (1000000);
        self.dropzone = new Dropzone("div.item-photo-dropzone", {
            autoProcessQueue: false,
            url: "#",
            accept: function (file, done) {
                if (file.type !== 'image/jpeg' && file.type !== 'image/png' && file.type !== 'image/gif') {
                    self.fileErrors.push('"' + file.name + '" not a valid image type (.jpeg, .png, .gif)');
                    this.removeFile(file);
                } else if (file.size > dzMaxFileSize) {
                    self.fileErrors.push('"' + file.name + '" file size over 5MB');
                    this.removeFile(file);
                } else {
                    done();
                }
            },
            previewTemplate: '<div class="dz-image-row"><div class="dz-image"><img data-dz-thumbnail></div><div class="dz-file-details"><span data-dz-name class="file-name"></span><span class="file-size" data-dz-size></span></div><div class="link-remove"><i class="fa fa-close" data-dz-remove></i></div></div>',
            init: function () {
                this.on("addedfile", function (file) {
                    self.uploadedFiles.push(file);
                });
                this.on("removedfile", function (file) {
                    self.uploadedFiles = _.reject(self.uploadedFiles, file);
                })
            }
        });
    }
});