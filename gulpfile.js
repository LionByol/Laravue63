const elixir = require('laravel-elixir');

require('laravel-elixir-vue');

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

elixir(mix => {
    mix.sass('app.scss');


    mix.copy('node_modules/moment/min/moment-with-locales.min.js','public/js/moment.js');
    mix.copy('node_modules/font-awesome/fonts', 'public/fonts');

    mix.webpack('app.js');

    mix.browserSync({
        proxy: 'laravue53.app'
    });
});
