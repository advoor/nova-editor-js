NovaEditorJS.booting(function (editorConfig, fieldConfig) {
    if (fieldConfig.toolSettings.delimiter.activated === true) {
        editorConfig.tools.delimiter = {
            class: require('@editorjs/delimiter'),
        }
    }
});
