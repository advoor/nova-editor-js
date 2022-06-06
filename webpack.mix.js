let mix = require('laravel-mix')

require('./nova.mix')

mix.setPublicPath('dist')
    .js('resources/js/index.js', 'js/field.js')
    .vue({ version: 3 })
    .css('resources/css/field.css', 'csxs')
    .nova('advoor/nova-editor-js')
