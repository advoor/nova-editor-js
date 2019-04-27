<template>
    <default-field :field="field" :errors="errors" :fullWidthContent="true">
        <template slot="field">
            <div :id="'editor-js-' + this.field.attribute"></div>
        </template>
    </default-field>
</template>

<script>
    import {FormField, HandlesValidationErrors} from 'laravel-nova'

    const EditorJS = require('@editorjs/editorjs');
    const Paragraph = require('@editorjs/paragraph');

    function setHeadingToolSettings(self, tools) {
        if (self.field.toolSettings.header.activated === true) {
            const Header = require('@editorjs/header');

            tools.header = {
                class: Header,
                config: {
                    placeholder: self.field.toolSettings.header.placeholder
                },
                shortcut: self.field.toolSettings.header.shortcut
            }
        }
    }

    function setListToolSettings(self, tools) {
        if (self.field.toolSettings.list.activated === true) {
            const List = require('@editorjs/list');

            tools.list = {
                class: List,
                inlineToolbar: self.field.toolSettings.list.inlineToolbar,
                shortcut: self.field.toolSettings.list.shortcut
            }
        }
    }

    function setCodeToolSettings(self, tools) {
        if (self.field.toolSettings.code.activated === true) {
            const CodeTool = require('@editorjs/code');

            tools.code = {
                class: CodeTool,
                shortcut: self.field.toolSettings.code.shortcut,
                config: {
                    placeholder: self.field.toolSettings.code.placeholder,
                },
            }
        }
    }

    function setLinkToolSettings(self, tools) {
        if (self.field.toolSettings.link.activated === true) {
            const LinkTool = require('@editorjs/link');

            tools.linkTool = {
                class: LinkTool,
                config: {
                    endpoint: self.field.fetchUrlEndpoint,
                }
            }
        }
    }

    function setImageToolSettings(self, tools) {
        if (self.field.toolSettings.image.activated === true) {
            const ImageTool = require('@editorjs/image');

            tools.image = {
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
            }
        }
    }

    function setInlineCodeToolSettings(self, tools) {
        if (self.field.toolSettings.inlineCode.activated === true) {
            const InlineCode = require('@editorjs/inline-code');

            tools.inlineCode = {
                class: InlineCode,
                shortcut: self.field.toolSettings.inlineCode.shortcut,
            }
        }
    }

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
                let tools = {};

                setHeadingToolSettings(self, tools);
                setListToolSettings(self, tools);
                setCodeToolSettings(self, tools);
                setLinkToolSettings(self, tools);
                setImageToolSettings(self, tools);
                setInlineCodeToolSettings(self, tools);

                var editor = new EditorJS({
                    /**
                     * Wrapper of Editor
                     */
                    holderId: 'editor-js-' + self.field.attribute,

                    /**
                     * Tools list
                     */
                    tools,

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
