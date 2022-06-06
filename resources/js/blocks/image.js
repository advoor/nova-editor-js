NovaEditorJS.booting((editorConfig, fieldConfig) => {
    if (fieldConfig.toolSettings.image.activated === true) {
        editorConfig.tools.image = {
            class: require('@editorjs/image'),
            config: {
                endpoints: {
                    byFile: fieldConfig.uploadImageByFileEndpoint,
                    byUrl: fieldConfig.uploadImageByUrlEndpoint,
                },
                additionalRequestHeaders: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            },
        };
    }
});
