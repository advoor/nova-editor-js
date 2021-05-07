NovaEditorJS.booting(function (editorConfig, fieldConfig) {
    if (fieldConfig.toolSettings.inlineCode.activated === true) {
        editorConfig.tools.inlineCode = {
            class: require('@editorjs/inline-code'),
            tunes: fieldConfig.toolSettings.inlineCode.tunes,
            shortcut: fieldConfig.toolSettings.inlineCode.shortcut,
        }
    }
});
