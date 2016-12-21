const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

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
    mix.sass('app.scss')
        .styles([
            'resources/assets/css/vendor/datepicker/datepicker3.css',
            'resources/assets/css/vendor/toastr/toastr.min.css',
            '../../../node_modules/vue-material/dist/vue-material.css'
        ])
        .webpack('app.js');
});
