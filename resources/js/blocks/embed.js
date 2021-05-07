NovaEditorJS.booting(function (editorConfig, fieldConfig) {
    if (fieldConfig.toolSettings.embed.activated === true) {
        editorConfig.tools.embed = {
            class: require('@editorjs/embed'),
            inlineToolbar: fieldConfig.toolSettings.embed.inlineToolbar,
            tunes: fieldConfig.toolSettings.embed.tunes,
            config: {
                services: {
                    codepen: fieldConfig.toolSettings.embed.services.codepen,
                    imgur: fieldConfig.toolSettings.embed.services.imgur,
                    vimeo: fieldConfig.toolSettings.embed.services.vimeo,
                    youtube: fieldConfig.toolSettings.embed.services.youtube,
                }
            }
        }
    }
});
