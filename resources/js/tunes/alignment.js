NovaEditorJS.booting(function (editorConfig, fieldConfig) {
    if (fieldConfig.toolSettings.alignment.activated === true) {
        editorConfig.tools.alignment = {
            class: require('editorjs-text-alignment-blocktune'),
            config: {
                default: fieldConfig.toolSettings.alignment.default
            }
        }
    }
});
