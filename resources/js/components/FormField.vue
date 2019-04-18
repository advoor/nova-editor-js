<template>
    <default-field :field="field" :errors="errors" :fullWidthContent="true">
        <template slot="field">
            <div id="editorjs"></div>
        </template>
    </default-field>
</template>

<script>
    import {FormField, HandlesValidationErrors} from 'laravel-nova'

    const EditorJS = require('@editorjs/editorjs');
    const Paragraph = require('@editorjs/paragraph');
    const ImageTool = require('@editorjs/image');
    const CodeTool = require('@editorjs/code');
    const Header = require('@editorjs/header');
    const List = require('@editorjs/list');
    const LinkTool = require('@editorjs/link');
    const InlineCode = require('@editorjs/inline-code');

    export default {
        mixins: [FormField, HandlesValidationErrors],

        props: ['resourceName', 'resourceId', 'field'],

        methods: {
            /*
             * Set the initial, internal value for the field.
             */
            setInitialValue() {

                let self = this;
                let currentContent = JSON.parse(self.field.value);

                var editor = new EditorJS({
                    /**
                     * Wrapper of Editor
                     */
                    holderId: 'editorjs',
                    /**
                     * Tools list
                     */
                    tools: {
                        header: {
                            class: Header,
                            inlineToolbar: ['link'],
                            config: {
                                placeholder: 'Header'
                            },
                            shortcut: 'CMD+SHIFT+H'
                        },
                        list: {
                            class: List,
                            inlineToolbar: true,
                            shortcut: 'CMD+SHIFT+L'
                        },
                        code: {
                            class: CodeTool,
                            shortcut: 'CMD+SHIFT+C'
                        },
                        linkTool: {
                            class: LinkTool,
                            config: {
                                endpoint: self.field.fetchUrlEndpoint,
                            }
                        },
                        image: {
                            class: ImageTool,
                            config: {
                                endpoints: {
                                    byFile: self.field.uploadImageByFileEndpoint,
                                    byUrl: self.field.uploadImageByUrlEndpoint,
                                },
                                additionalRequestHeaders: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            }
                        },
                        inlineCode: {
                            class: InlineCode,
                            shortcut: 'CMD+SHIFT+M',
                        },
                    },
                    /**
                     * This Tool will be used as default
                     */
                    initialBlock: 'paragraph',

                    /**
                     * Initial Editor data
                     */
                    data: currentContent,
                    onReady: function () {

                    },
                    onChange: function () {
                        editor.save().then((savedData) => {
                            self.handleChange(savedData)
                        });
                    }
                });
            },

            /**
             * Fill the given FormData object with the field's internal value.
             */
            fill(formData) {
                formData.append(this.field.attribute, this.value || '')
            },

            /**
             * Update the field's internal value.
             */
            handleChange(value) {
                this.value = JSON.stringify(value)
            },
        },
    }
</script>
