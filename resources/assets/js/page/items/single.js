Vue.component('item-single', {
    name: 'itemSingle',
    el: function () {
        return '#item-single'
    },
    data: function () {
        return {};
    },
    props: [],
    computed: {},
    methods: {},
    events: {},
    ready: function () {
        var itemPhotoDropzone = new Dropzone("#item-photo-uploader", {
            autoProcessQueue: true,
            maxFilesize: 5,
            previewTemplate: '<div class="dz-image-row">' +
            '                       <div class="dz-image">' +
            '                           <img data-dz-thumbnail>' +
            '                       </div>' +
            '                       <div class="dz-file-details">' +
            '                           <div class="name-status">' +
            '                               <span data-dz-name class="file-name"></span>' +
            '                               <div class="dz-success-mark status-marker"><span>✔</span></div>' +
            '                               <div class="dz-error-mark status-marker"><span>✘</span></div>' +
            '                           </div>' +
            '                           <span class="file-size" data-dz-size></span>' +
            '                           <div class="dz-progress progress">' +
            '                               <span class="dz-upload progress-bar progress-bar-striped active" data-dz-uploadprogress></span>' +
            '                           </div>' +
            '                       </div>' +
            '                </div>',
            init: function () {
                // this.on("addedfile", function (file) {
                //     self.uploadedFiles.push(file);
                // });
                // this.on("removedfile", function (file) {
                //     self.uploadedFiles = _.reject(self.uploadedFiles, file);
                // })
                this.on("complete", function (file) {
                    setTimeout(function () {
                        this.removeFile(file);
                    }.bind(this), 5000);
                });
                this.on("success", function (files, response) {
                    $imgGallery = $('.image-gallery');
                    if (!$imgGallery.length) {
                        $('.main-image').after('<ul class="image-gallery list-unstyled">' +
                            '<li>' +
                            '<a href="' + response.path + '" rel="group" class="fancybox">' +
                            '   <img src="' + response.thumbnail_path + '" alt="Item photo">' +
                            '</a>' +
                            '</li>' +
                            '</ul>')
                    } else {
                        $imgGallery.append('<li>' +
                            '<a href="' + response.path + '" rel="group" class="fancybox">' +
                            '   <img src="' + response.thumbnail_path + '" alt="Item photo">' +
                            '</a>' +
                            '</li>');
                    }

                });
            }
        });
    }
});