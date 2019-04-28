<template>
    <default-field :field="field" :errors="errors" :fullWidthContent="true">
        <template slot="field">
            <div :id="'editor-js-' + this.field.attribute" class="editor-js"></div>
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

    function setChecklistToolSettings(self, tools) {
        if (self.field.toolSettings.checklist.activated === true) {
            const Checklist = require('@editorjs/checklist');

            tools.checklist = {
                class: Checklist,
                inlineToolbar: self.field.toolSettings.checklist.inlineToolbar,
                shortcut: self.field.toolSettings.checklist.shortcut
            }
        }
    }

    function setMarkerToolSettings(self, tools) {
        if (self.field.toolSettings.marker.activated === true) {
            const Marker = require('@editorjs/marker');

            tools.marker = {
                class: Marker,
                shortcut: self.field.toolSettings.marker.shortcut
            }
        }
    }

    function setDelimiterToolSettings(self, tools) {
        if (self.field.toolSettings.delimiter.activated === true) {
            const Delimiter = require('@editorjs/delimiter');

            tools.delimiter = {
                class: Delimiter,
            }
        }
    }

    function setTableToolSettings(self, tools) {
        if (self.field.toolSettings.table.activated === true) {
            const Table = require('@editorjs/table');

            tools.table = {
                class: Table,
                inlineToolbar: self.field.toolSettings.table.inlineToolbar,
            }
        }
    }

    function setEmbedToolSettings(self, tools) {
        if (self.field.toolSettings.embed.activated === true) {
            const Embed = require('@editorjs/embed');

            tools.embed = {
                class: Embed,
                inlineToolbar: self.field.toolSettings.embed.inlineToolbar,
                config: {
                    services: {
                        codepen: self.field.toolSettings.embed.services.codepen,
                        imgur: self.field.toolSettings.embed.services.imgur,
                        vimeo: self.field.toolSettings.embed.services.vimeo,
                        youtube: self.field.toolSettings.embed.services.youtube,
                    }
                }
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

                this.value = this.field.value;

                let self = this;
                let currentContent = (self.field.value ? JSON.parse(self.field.value) : self.field.value);
                let tools = {};

                setHeadingToolSettings(self, tools);
                setListToolSettings(self, tools);
                setCodeToolSettings(self, tools);
                setLinkToolSettings(self, tools);
                setImageToolSettings(self, tools);
                setInlineCodeToolSettings(self, tools);
                setChecklistToolSettings(self, tools);
                setMarkerToolSettings(self, tools);
                setDelimiterToolSettings(self, tools);
                setTableToolSettings(self, tools);
                setEmbedToolSettings(self, tools);

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
