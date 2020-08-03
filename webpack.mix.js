let mix = require('laravel-mix')

mix.setPublicPath('dist')
    .js('resources/js/index.js', 'js/field.js')
    .sass('resources/sass/field.scss', 'css')
