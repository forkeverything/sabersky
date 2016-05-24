Vue.component('item-single', {
    name: 'itemSingle',
    el: function () {
        return '#item-single'
    },
    data: function () {
        return {
            ajaxReady: true,
            photos: [],
            fileErrors: []
        };
    },
    props: ['itemId'],
    computed: {},
    methods: {
        deletePhoto: function(photo) {
            var self = this;
            if(!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/api/items/' + self.itemId + '/photo/' + photo.id,
                method: 'DELETE',
                success: function(data) {
                   // success
                    console.log(data);
                   self.photos = _.reject(self.photos, photo);
                   self.ajaxReady = true;
                },
                error: function(response) {
                    self.ajaxReady = true;
                }
            });
        },
        clearErrors: function() {
            this.fileErrors = [];
        }
    },
    events: {},
    mixins: [userCompany],
    ready: function () {

        var self = this;

        // Fetch item photos
        $.ajax({
            url: '/api/items/' + self.itemId,
            method: 'GET',
            success: function(data) {
               // success
                self.photos = data.photos
            },
            error: function(response) {
                console.log(response);
            }
        });

        new Dropzone("#item-photo-uploader", {
            autoProcessQueue: true,
            maxFilesize: 5,
            acceptedFiles: 'image/*',
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
                this.on("complete", function (file) {
                    setTimeout(function () {
                        this.removeFile(file);
                    }.bind(this), 5000);
                });
                this.on("success", function (files, response) {
                    // Upload was successful, receive response
                    // of Photo Model back from the server.
                    self.photos.push(response);
                });
                this.on("error", function (file, err) {
                    if(typeof err === 'object') {
                        _.forEach(err.file, function (error) {
                            self.fileErrors.push(file.name + ': ' + error);
                        });
                    } else {
                        self.fileErrors.push(file.name + ': ' + err);
                    }
                });
            }
        });
    }
});