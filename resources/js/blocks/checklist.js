NovaEditorJS.booting((editorConfig, fieldConfig) => {
    if (fieldConfig.toolSettings.checklist.activated === true) {
        editorConfig.tools.checklist = {
            class: require('@editorjs/checklist'),
            inlineToolbar: fieldConfig.toolSettings.checklist.inlineToolbar,
            shortcut: fieldConfig.toolSettings.checklist.shortcut,
        };
    }
});
