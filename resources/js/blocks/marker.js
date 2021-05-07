NovaEditorJS.booting(function (editorConfig, fieldConfig) {
    if (fieldConfig.toolSettings.marker.activated === true) {
        editorConfig.tools.marker = {
            class: require('@editorjs/marker'),
            tunes: fieldConfig.toolSettings.marker.tunes,
            shortcut: fieldConfig.toolSettings.marker.shortcut
        }
    }
});
