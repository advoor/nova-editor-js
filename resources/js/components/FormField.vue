<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <div id="editorjs"></div>
        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
const EditorJS = require('@editorjs/editorjs');
const Paragraph = require('@editorjs/paragraph');
const ImageTool = require('@editorjs/image');
const CodeTool = require('@editorjs/code');
const Header = require('@editorjs/header');
const List = require('@editorjs/list');
const LinkTool = require('@editorjs/link');



export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {

            var editor = new EditorJS({
                /**
                 * Wrapper of Editor
                 */
                holderId: 'editorjs',
                /**
                 * Tools list
                 */
                tools: {
                    /**
                     * Each Tool is a Plugin. Pass them via 'class' option with necessary settings {@link docs/tools.md}
                     */
                    header: {
                        class: Header,
                        inlineToolbar: ['link'],
                        config: {
                            placeholder: 'Header'
                        },
                        shortcut: 'CMD+SHIFT+H'
                    },
                    /**
                     * Or pass class directly without any configuration
                     */
                    list: {
                        class: List,
                        inlineToolbar: true,
                        shortcut: 'CMD+SHIFT+L'
                    },
                    code: {
                        class:  CodeTool,
                        shortcut: 'CMD+SHIFT+C'
                    },
                    linkTool: LinkTool,
                    image: {
                        class: ImageTool,
                        config: {
                            endpoints: {
                                byFile: 'http://localhost:8008/uploadFile', // Your backend file uploader endpoint
                                byUrl: 'http://localhost:8008/fetchUrl', // Your endpoint that provides uploading by Url
                            }
                        }
                    },
                },
                /**
                 * This Tool will be used as default
                 */
                // initialBlock: 'paragraph',
                /**
                 * Initial Editor data
                 */
                data: {
                    blocks: [
                        {
                            type: "header",
                            data: {
                                text: "Editor.js",
                                level: 2
                            }
                        },
                        {
                            type : 'paragraph',
                            data : {
                                text : 'Hey. Meet the new Editor. On this page you can see it in action — try to edit this text. Source code of the page contains the example of connection and configuration.'
                            }
                        },
                        {
                            type: "header",
                            data: {
                                text: "Key features",
                                level: 3
                            }
                        },
                        {
                            type : 'list',
                            data : {
                                items : [
                                    'It is a block-styled editor',
                                    'It returns clean data output in JSON',
                                    'Designed to be extendable and pluggable with a simple API',
                                ],
                                style: 'unordered'
                            }
                        },
                        {
                            type: "header",
                            data: {
                                text: "What does it mean «block-styled editor»",
                                level: 3
                            }
                        },
                        {
                            type : 'paragraph',
                            data : {
                                text : 'Workspace in classic editors is made of a single contenteditable element, used to create different HTML markups. Editor.js <mark class=\"cdx-marker\">workspace consists of separate Blocks: paragraphs, headings, images, lists, quotes, etc</mark>. Each of them is an independent contenteditable element (or more complex structure) provided by Plugin and united by Editor\'s Core.'
                            }
                        },
                        {
                            type : 'paragraph',
                            data : {
                                text : `There are dozens of <a href="https://github.com/editor-js">ready-to-use Blocks</a> and the <a href="https://editorjs.io/creating-a-block-tool">simple API</a> for creation any Block you need. For example, you can implement Blocks for Tweets, Instagram posts, surveys and polls, CTA-buttons and even games.`
                            }
                        },
                        {
                            type: "header",
                            data: {
                                text: "What does it mean clean data output",
                                level: 3
                            }
                        },
                        {
                            type : 'paragraph',
                            data : {
                                text : 'Classic WYSIWYG-editors produce raw HTML-markup with both content data and content appearance. On the contrary, Editor.js outputs JSON object with data of each Block. You can see an example below'
                            }
                        },
                        {
                            type : 'paragraph',
                            data : {
                                text : `Given data can be used as you want: render with HTML for <code class="inline-code">Web clients</code>, render natively for <code class="inline-code">mobile apps</code>, create markup for <code class="inline-code">Facebook Instant Articles</code> or <code class="inline-code">Google AMP</code>, generate an <code class="inline-code">audio version</code> and so on.`
                            }
                        },
                        {
                            type : 'paragraph',
                            data : {
                                text : 'Clean data is useful to sanitize, validate and process on the backend.'
                            }
                        },
                        {
                            type : 'paragraph',
                            data : {
                                text : 'We have been working on this project more than three years. Several large media projects help us to test and debug the Editor, to make it\'s core more stable. At the same time we significantly improved the API. Now, it can be used to create any plugin for any task. Hope you enjoy. 😏'
                            }
                        },
                        {
                            type: 'image',
                            data: {
                                "file": {
                                    "url" : "https://www.tesla.com/tesla_theme/assets/img/_vehicle_redesign/roadster_and_semi/roadster/hero.jpg"
                                },
                                "caption" : "Roadster // tesla.com",
                                "withBorder" : false,
                                "withBackground" : false,
                                "stretched" : true
                            }
                        },
                    ]
                },
                onReady: function(){

                },
                onChange: function() {
                    console.log('something changed');
                }
            });


            this.value = this.field.value || ''
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
            this.value = value
        },
    },
}
</script>
