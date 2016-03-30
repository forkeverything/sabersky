(function () {
    $('#purchase-requests-add .input-item-photos').fileinput({
        'showUpload': false,
        'allowedFileExtensions': ['jpg', 'gif', 'png'],
        'showRemove': false,
        'showCaption': false,
        'previewSettings': {
            image: {width: "120px", height: "120px"}
        },
        'browseLabel': 'Photo',
        'browseIcon': '<i class="fa fa-plus"></i> &nbsp;',
        'browseClass': 'btn btn-outline-grey',
        'layoutTemplates': {
            preview: '<div class="file-preview {class}">\n' +
            '    <div class="close fileinput-remove">Clear</div>\n' +
            '    <div class="{dropClass}">\n' +
            '    <div class="file-preview-thumbnails">\n' +
            '    </div>\n' +
            '    <div class="clearfix"></div>' +
            '    <div class="file-preview-status text-center text-success"></div>\n' +
            '    <div class="kv-fileinput-error"></div>\n' +
            '    </div>\n' +
            '</div>'
        }
    });
})();

(function () {
    var uploadUrl = 'http:' + $('#form-item-photo').attr('action');
    var $input = $('#purchase-request-single .input-item-photos');
    $input.fileinput({
        uploadUrl: uploadUrl,
        uploadAsync: true,
        allowedFileExtensions: ['jpg', 'gif', 'png'],
        showRemove: false,
        showCaption: false,
        showPreview: false,
        showCancel: false,
        showUpload: false,
        browseIcon: '<i class="fa fa-plus"></i> &nbsp;',
        browseClass: 'btn btn-outline-grey',
        browseLabel: 'Photo'
    }).on("filebatchselected", function (event, files) {
        $input.fileinput("upload");
    }).on('filebatchuploadcomplete', function (event, files, extra) {
        location.reload();
    });
})();
