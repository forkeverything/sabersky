Vue.component('add-item-modal', {
    name: 'addItemModal',
    template: '<div class="modal-item-add" v-show="visible">' +
    '<form class="form-item-add">' +
    '   <div class="form-group">' +
    '       <label for="">SKU</label>' +
    '       <input class="form-control" type="text" placeholder="SKU" v-model="sku">' +
    '   </div>' +
    '   <div class="form-group">' +
    '       <label for="">Brand</label>' +
    '       <input class="form-control" type="text" placeholder="Brand" v-model="brand">' +
    '   </div>' +
    '   <div class="form-group">' +
    '       <label for="" class="required">Name</label>' +
    '       <input class="form-control" type="text" placeholder="Name" v-model="name">' +
    '   </div>' +
    '   <div class="form-group">' +
    '       <label for="" class="required">Specification</label>' +
    '       <textarea class="autosize form-control" v-model="specification"></textarea>' +
    '   </div>' +
        '<input class="item-photos-upload" type="file" name="item_photos[]" multiple="multiple">' +
    '   <button type="button"' +
    '           class="btn btn-outline-green"' +
    '           @click.prevent="submitAddItemForm"' +
    '           :disabled="! canSubmitForm"' +
    '   >' +
    '       Save Item' +
    '   </button>' +
    '</form>' +
    '</div>',
    data: function() {
        return {
            existingBrands: [],
            existingNames: [],
            sku: '',
            brand: '',
            name: '',
            specification: '',
            uploadedfiles: []
        };
    },
    props: ['visible'],
    computed: {
        canSubmitForm: function() {
            return this.name && this.specification
        }
    },
    methods: {
        submitAddItemForm: function() {

        }
    },
    events: {

    },
    ready: function() {

        

        // File Upload
        $('.item-photos-upload').fileupload({
            autoUpload: false
        }).on('fileuploadadd', function (e, data) {
            _.forEach(data.files, function(file) {
                self.uploadedFiles.push(file);
            });
        });
    }
});