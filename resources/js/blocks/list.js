NovaEditorJS.booting((editorConfig, fieldConfig) => {
    if (fieldConfig.toolSettings.list.activated === true) {
        editorConfig.tools.list = {
            class: require('@editorjs/list'),
            inlineToolbar: fieldConfig.toolSettings.list.inlineToolbar,
            shortcut: fieldConfig.toolSettings.list.shortcut,
            config: {
                defaultStyle: fieldConfig.toolSettings.list.defaultStyle,
            },
        };
    }
});
