$(document).ready(function () {

    // Moment JS
    moment.locale('id'); // 'en'

    // Bootstrap Datepicker
    $('.datepicker').datepicker({
        format: "dd/mm/yyyy",
        startDate: 'today',
        language: 'id'
    });

    // Dropzone
    Dropzone.options.addPhotosForm = {
        paramName: 'photo',                             // name of the input, in controller: $request->file('photo')
        maxFileSize: 3,                                 // File size in Mb
        acceptedFiles: '.jpg, .jpeg, .png, .bmp',       // file formats accepted
    }
});
