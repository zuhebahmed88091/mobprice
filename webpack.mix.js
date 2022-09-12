const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// mix.sass('resources/sass/frontend.scss', 'public/css').sourceMaps();

// frontend js
mix.combine([
    'resources/assets/themes/innolytic/js/jquery-min.js',
    'resources/assets/themes/innolytic/js/popper.min.js',
    'resources/assets/themes/innolytic/js/bootstrap.min.js',
    'resources/assets/themes/innolytic/js/header.min.js',
    'resources/assets/themes/innolytic/js/owl.carousel.min.js',
    'resources/assets/jquery-typeahead-2.11.0/jquery.typeahead.min.js',
    'resources/assets/alertify/alertify.min.js',
    'resources/assets/vendor/jquery-validation/dist/jquery.validate.min.js',
    'resources/assets/vendor/select2/dist/js/select2.full.min.js',
    'resources/assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
    'resources/assets/vendor/iCheck/icheck.min.js',
    'resources/assets/themes/innolytic/js/required.js',
], 'public/js/frontend.js').sourceMaps();
