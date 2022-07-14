const mix = require("laravel-mix");

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

mix.js("resources/js/app.js", "public/js")
    .sass("resources/sass/app.scss", "public/css")
    .sourceMaps();
/* 
//Enrollment
mix.js("resources/js/enrollment/create.js", "public/js/enrollment/create.js");
mix.js("resources/js/enrollment/edit.js", "public/js/enrollment/edit.js");

//Incidentes
mix.js("resources/js/incidentes/index.js", "public/js/incidentes/index.js"); */
