NovaEditorJS.booting((editorConfig, fieldConfig) => {
    if (fieldConfig.toolSettings.header.activated === true) {
        editorConfig.tools.header = {
            class: require('@editorjs/header'),
            inlineToolbar: fieldConfig.toolSettings.header.inlineToolbar || true,
            config: {
                placeholder: fieldConfig.toolSettings.header.placeholder,
                levels: fieldConfig.toolSettings.header.levels || [1, 2, 3, 4, 5],
                defaultLevel: fieldConfig.toolSettings.header.defaultLevel || 1,
            },
            shortcut: fieldConfig.toolSettings.header.shortcut,
        };
    }
});
