NovaEditorJS.booting(function (editorConfig, fieldConfig) {
    if (fieldConfig.toolSettings.list.activated === true) {
        editorConfig.tools.list = {
            class: require('@editorjs/list'),
            tunes: fieldConfig.toolSettings.list.tunes,
            inlineToolbar: fieldConfig.toolSettings.list.inlineToolbar,
            shortcut: fieldConfig.toolSettings.list.shortcut
        }
    }
});
