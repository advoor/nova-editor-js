<template>
    <DefaultField
        :field="field"
        :errors="errors"
        :show-help-text="showHelpText"
        :full-width-content="true"
        @keydown.stop
    >
        <template #field>
            <div
                :id="`editor-js-${field.attribute}`"
                ref="input"
                class="editor-js"
            />
        </template>
    </DefaultField>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    methods: {
        /*
             * Set the initial, internal value for the field.
             */
        setInitialValue() {
            this.value = this.field.value;

            const self = this;

            const currentContent = (typeof self.field.value === 'object')
                ? self.field.value
                : JSON.parse(self.field.value);

            const editor = NovaEditorJS.getInstance({
                /**
                     * Wrapper of Editor
                     */
                holderId: `editor-js-${self.field.attribute}`,

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

                /**
                     * Min height of editor
                     */
                minHeight: 35,

                onReady() {

                },
                onChange() {
                    editor.save().then((savedData) => {
                        self.handleChange(savedData);
                    });
                },
            }, self.field);
        },

        /**
             * Fill the given FormData object with the field's internal value.
             */
        fill(formData) {
            formData.append(this.field.attribute, this.value || '');
        },

        /**
             * Update the field's internal value.
             */
        handleChange(value) {
            this.value = JSON.stringify(value);
        },
    },
};
</script>
