Nova.booting((Vue, router, store) => {
    Vue.component('index-nova-editor-js', require('./components/IndexField'))
    Vue.component('detail-nova-editor-js', require('./components/DetailField'))
    Vue.component('form-nova-editor-js', require('./components/FormField'))
})
