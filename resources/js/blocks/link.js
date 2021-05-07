NovaEditorJS.booting(function (editorConfig, fieldConfig) {
    if (fieldConfig.toolSettings.link.activated === true) {
        editorConfig.tools.linkTool = {
            class: require('@editorjs/link'),
            tunes: fieldConfig.toolSettings.linkTool.tunes,
            config: {
                endpoint: fieldConfig.fetchUrlEndpoint,
            }
        }
    }
});
