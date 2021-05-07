NovaEditorJS.booting(function (editorConfig, fieldConfig) {
    if (fieldConfig.toolSettings.link.activated === true) {
        editorConfig.tools.linkTool = {
            class: require('@editorjs/link'),
            tunes: fieldConfig.toolSettings.link.tunes,
            config: {
                endpoint: fieldConfig.fetchUrlEndpoint,
            }
        }
    }
});
