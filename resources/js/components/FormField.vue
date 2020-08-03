<template>
    <default-field @keydown.native.stop :field="field" :errors="errors" :fullWidthContent="true">
        <template slot="field">
            <div :id="'editor-js-' + this.field.attribute" class="editor-js"></div>
        </template>
    </default-field>
</template>

<script>
    import {FormField, HandlesValidationErrors} from 'laravel-nova';

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

                let editor = NovaEditorJS.getInstance({
                    /**
                     * Wrapper of Editor
                     */
                    holderId: 'editor-js-' + self.field.attribute,

                    /**
                     * This Tool will be used as default
                     */
                    initialBlock: self.field.editorSettings.initialBlock,

                    /**
                     * Default placeholder
                     */
                    placeholder: self.field.editorSettings.placeholder,

                    /**
                     * Enable autofocus
                     */
                    autofocus: self.field.editorSettings.autofocus,

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
                }, self.field);
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
