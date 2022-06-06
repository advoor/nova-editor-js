Nova.booting((Vue) => {
    Vue.component('IndexNovaEditorJs', require('./components/IndexField').default);
    Vue.component('DetailNovaEditorJs', require('./components/DetailField').default);
    Vue.component('FormNovaEditorJs', require('./components/FormField').default);
});
