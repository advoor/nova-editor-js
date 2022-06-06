// Import the Nova Editor class
import NovaEditorJS from './nova-editor';

// Expose it for other plugins
window.NovaEditorJS = new NovaEditorJS();

// Import the blocks
require('./blocks/checklist');
require('./blocks/code');
require('./blocks/delimiter');
require('./blocks/embed');
require('./blocks/heading');
require('./blocks/image');
require('./blocks/inline-code');
require('./blocks/link');
require('./blocks/list');
require('./blocks/marker');
require('./blocks/paragraph');
require('./blocks/raw');
require('./blocks/table');

// Import the Nova field declaration
require('./field');
