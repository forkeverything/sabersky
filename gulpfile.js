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
        'font-awesome/css/font-awesome.min.css'
    ], 'public/css/all.css', 'resources/assets/bower');

    mix.copy('resources/assets/bower/font-awesome/fonts', 'public/fonts');

    mix.scripts([
        'jquery/dist/jquery.min.js',
        'bootstrap-sass/assets/javascripts/bootstrap.min.js'
    ], 'public/js/vendor.js', 'resources/assets/bower');

    mix.browserSync({proxy: 'pusakagroup.app'});
});
