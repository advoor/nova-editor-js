NovaEditorJS.booting((editorConfig, fieldConfig) => {
    if (fieldConfig.toolSettings.inlineCode.activated === true) {
        editorConfig.tools.inlineCode = {
            class: require('@editorjs/inline-code'),
            shortcut: fieldConfig.toolSettings.inlineCode.shortcut,
        };
    }
});
