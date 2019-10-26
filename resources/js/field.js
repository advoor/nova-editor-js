Nova.booting((Vue, router, store) => {
    Vue.component('index-nova-editor-js', require('./components/IndexField').default)
    Vue.component('detail-nova-editor-js', require('./components/DetailField').default)
    Vue.component('form-nova-editor-js', require('./components/FormField').default)
})
