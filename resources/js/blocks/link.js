NovaEditorJS.booting((editorConfig, fieldConfig) => {
    if (fieldConfig.toolSettings.link.activated === true) {
        editorConfig.tools.linkTool = {
            class: require('@editorjs/link'),
            config: {
                endpoint: fieldConfig.fetchUrlEndpoint,
            },
        };
    }
});
