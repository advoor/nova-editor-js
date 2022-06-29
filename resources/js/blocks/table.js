NovaEditorJS.booting((editorConfig, fieldConfig) => {
    if (fieldConfig.toolSettings.table.activated === true) {
        editorConfig.tools.table = {
            class: require('@editorjs/table'),
            inlineToolbar: fieldConfig.toolSettings.table.inlineToolbar,
        };
    }
});
