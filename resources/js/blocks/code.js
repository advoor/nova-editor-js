NovaEditorJS.booting((editorConfig, fieldConfig) => {
    if (fieldConfig.toolSettings.code.activated === true) {
        editorConfig.tools.code = {
            class: require('@editorjs/code'),
            shortcut: fieldConfig.toolSettings.code.shortcut,
            config: {
                placeholder: fieldConfig.toolSettings.code.placeholder,
            },
        };
    }
});
