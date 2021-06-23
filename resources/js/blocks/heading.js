NovaEditorJS.booting(function (editorConfig, fieldConfig) {
    if (fieldConfig.toolSettings.header.activated === true) {
        editorConfig.tools.header = {
            class: require('@editorjs/header'),
            tunes: fieldConfig.toolSettings.header.tunes,
            config: {
                placeholder: fieldConfig.toolSettings.header.placeholder
            },
            shortcut: fieldConfig.toolSettings.header.shortcut,
            inlineToolbar: fieldConfig.toolSettings.header.inlineToolbar,
        }
    }
});
