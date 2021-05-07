NovaEditorJS.booting(function (editorConfig, fieldConfig) {
    if (fieldConfig.toolSettings.code.activated === true) {
        editorConfig.tools.code = {
            class: require('@editorjs/code'),
            shortcut: fieldConfig.toolSettings.code.shortcut,
            tunes: fieldConfig.toolSettings.code.tunes,
            config: {
                placeholder: fieldConfig.toolSettings.code.placeholder,
            }
        }
    }
});
