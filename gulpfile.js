var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass('app.scss');

    mix.styles([
        // App
        '../../../public/css/app.css',
        // Packages
        'font-awesome/css/font-awesome.min.css',
        'bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
        'animate.css/animate.min.css',
        'selectize/dist/css/selectize.css',
        'selectize/dist/css/selectize.bootstrap3.css',
        'dropzone/dist/min/dropzone.min.css',
        'bootstrap-fileinput/css/fileinput.min.css',
        'fancybox/source/jquery.fancybox.css',
        'x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css',
        'bootstrap-select/dist/css/bootstrap-select.min.css',
        'blueimp-file-upload/css/jquery.fileupload.css',
        'toastr/toastr.min.css'
    ], 'public/css/all.css', 'resources/assets/bower');

    mix.copy('resources/assets/bower/font-awesome/fonts', 'public/fonts');

    mix.scripts([
        'jquery/dist/jquery.js',
        'bootstrap-sass/assets/javascripts/bootstrap.min.js',
        'vue/dist/vue.js',
        'lodash/lodash.js',
        'moment/min/moment-with-locales.min.js',
        'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
        'bootstrap-datepicker/dist/locales/bootstrap-datepicker.id.min.js',
        'selectize/dist/js/standalone/selectize.min.js',
        'dropzone/dist/min/dropzone.min.js',
        'bootstrap-fileinput/js/fileinput.min.js',
        'bootstrap-fileinput/js/fileinput_locale_id.js',
        'fancybox/lib/jquery.mousewheel-3.0.6.pack.js',
        'fancybox/source/jquery.fancybox.pack.js',
        'x-editable/dist/bootstrap3-editable/js/bootstrap-editable.js',
        'bootstrap-select/dist/js/bootstrap-select.min.js',
        'autosize/dist/autosize.min.js',
        'blueimp-file-upload/js/vendor/jquery.ui.widget.js',
        'blueimp-file-upload/js/jquery.fileupload.js',
        'js-cookie/src/js.cookie.js',
        'toastr/toastr.js',
        'accounting.js/accounting.min.js'
    ], 'public/js/vendor.js', 'resources/assets/bower');


    mix.scriptsIn('resources/assets/js/dependencies', 'public/js/dependencies.js');
    mix.scriptsIn('resources/assets/js/global', 'public/js/global.js');


    mix.scriptsIn('resources/assets/js/page/**/*', 'public/js/page.js');
    mix.copy('resources/assets/js/page/root.js', 'public/js/vue-root.js');

    mix.copy(
        [
            'resources/assets/bower/fancybox/source/fancybox_overlay.png',
            'resources/assets/bower/fancybox/source/fancybox_loading@2x.gif',
            'resources/assets/bower/fancybox/source/fancybox_loading.gif',
            'resources/assets/bower/fancybox/source/fancybox_sprite.png',
            'resources/assets/bower/fancybox/source/fancybox_sprite@2x.png',
            'resources/assets/bower/fancybox/source/blank.gif'
        ],
        'public/css');

    mix.browserSync({proxy: 'pusakagroup.app'});

    // mix.phpUnit(null, {group: 'driven'});
});
