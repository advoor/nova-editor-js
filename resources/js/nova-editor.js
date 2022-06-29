const EditorJS = require('@editorjs/editorjs');

export default class NovaEditorJS {
    constructor() {
        this.defaultConfigObject = {
            tools: {},
        };

        this.persistentConfigObject = {};
        this.bootingCallbacks = [];
    }

    /**
     * Callback for registering a plugin
     *
     * @callback novaEditorJSBooting
     * @param {Object} editorConfig Editor Config
     * @param {Object} fieldConfig Field Config
     */

    /**
     * Register a callback to load your block plugin.
     *
     * @param {novaEditorJSBooting} callback Callback to register your plugin
     */
    booting(callback) {
        // Only callables are allowed
        if (!(callback instanceof Function)) {
            return;
        }

        this.bootingCallbacks.push(callback);
    }

    getInstance(config, field) {
        const editorConfig = _.merge({}, this.defaultConfigObject, config);
        const fieldObject = _.cloneDeep(field);

        // Plugins should not modify the field config.
        // If a key should be changed, other plugins loaded later
        // would have an unsynchronized version of the field configuration.
        Object.freeze(fieldObject);

        // We boot each block plugin by passing the editorConfig and the fieldObject
        this.bootingCallbacks.forEach((callback) => callback(editorConfig, fieldObject));

        // We apply the persistent config and return the editor instance
        return new EditorJS(
            _.merge(editorConfig, this.persistentConfigObject),
        );
    }

    /**
     * Sets a default configuration for the editor. The values set here will
     * be overriden by the form field, if a key of the same name exists.
     *
     * @param config
     */
    defaultConfig(config) {
        // If it's not an object, we discard the information
        if (!(config instanceof Object)) {
            return;
        }

        // We use lodash to perform a deep merge, instead of overwriting
        // root values with the spread operator
        this.defaultConfigObject = _.merge(this.defaultConfigObject, config);
    }

    /**
     * Sets a persistent configuration for the editor. The values set here will
     * be overwrite any and all keys set by the form field, if a key of the
     * same name exists.
     *
     * @param config
     */
    persistentConfig(config) {
        // If it's not an object, we discard the information
        if (!(config instanceof Object)) {
            return;
        }

        // We use lodash to perform a deep merge, instead of overwriting
        // root values with the spread operator
        this.persistentConfigObject = _.merge(this.persistentConfigObject, config);
    }
}
