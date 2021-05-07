NovaEditorJS.booting(function (editorConfig, fieldConfig) {
    if (fieldConfig.toolSettings.table.activated === true) {
        editorConfig.tools.table = {
            class: require('@editorjs/table'),
            tunes: fieldConfig.toolSettings.table.tunes,
            inlineToolbar: fieldConfig.toolSettings.table.inlineToolbar,
        }
    }
});
