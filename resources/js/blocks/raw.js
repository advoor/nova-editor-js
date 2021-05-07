NovaEditorJS.booting(function (editorConfig, fieldConfig) {
    if (fieldConfig.toolSettings.raw.activated === true) {
        editorConfig.tools.raw = {
            class: require('@editorjs/raw'),
            tunes: fieldConfig.toolSettings.raw.tunes,
            config: {
                placeholder: fieldConfig.toolSettings.raw.placeholder,
            },
        }
    }
});
