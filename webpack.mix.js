const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

 mix.babel([
    'resources/assets/js/app.js',
    'resources/assets/js/other.js',
    'resources/assets/js/ajax.js',
    'resources/assets/js/form.js',
    'resources/assets/js/table.js',
    'resources/assets/js/ui.js',
    'resources/assets/js/validation.js',
    'resources/assets/js/grafik.js',
    'resources/assets/js/htmleditor.js',
    'resources/assets/js/sweet_alert.js'
    ], 'public/main.js')
    .styles([
	    'resources/assets/css/compiler.css',
	    'resources/assets/css/style.css',
	], 'public/main.css');
