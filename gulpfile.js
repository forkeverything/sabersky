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
        'animate.css/animate.min.css'
    ], 'public/css/all.css', 'resources/assets/bower');

    mix.copy('resources/assets/bower/font-awesome/fonts', 'public/fonts');

    mix.scripts([
        'jquery/dist/jquery.min.js',
        'bootstrap-sass/assets/javascripts/bootstrap.min.js',
        'vue/dist/vue.js',
        'lodash/lodash.js',
        'moment/min/moment-with-locales.min.js',
        'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
        'bootstrap-datepicker/dist/locales/bootstrap-datepicker.id.min.js',
        'noty/js/noty/packaged/jquery.noty.packaged.min.js'
    ], 'public/js/vendor.js', 'resources/assets/bower');

    mix.scriptsIn('resources/assets/js', 'public/js/app.js');

    mix.browserSync({proxy: 'pusakagroup.app'});
});
