NovaEditorJS.booting(function (editorConfig, fieldConfig) {
    editorConfig.tools.paragraph = {
        class: require('@editorjs/paragraph'),
        tunes: fieldConfig.toolSettings.paragraph.tunes,
        inlineToolbar: true
    }
});
