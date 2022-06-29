NovaEditorJS.booting((editorConfig) => {
    editorConfig.tools.paragraph = {
        class: require('@editorjs/paragraph'),
        inlineToolbar: true,
    };
});
