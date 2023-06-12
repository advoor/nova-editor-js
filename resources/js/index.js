// Import the Nova Editor class
import NovaEditorJS from './nova-editor';

// Expose it for other plugins
window.NovaEditorJS = new NovaEditorJS();

// Import the blocks
require('./blocks/heading');
require('./blocks/image');
require('./blocks/list');
require('./blocks/checklist');
require('./blocks/table');
require('./blocks/delimiter');
require('./blocks/code');
require('./blocks/raw');
require('./blocks/embed');
require('./blocks/inline-code');
require('./blocks/link');
require('./blocks/marker');
require('./blocks/paragraph');

// Import the Nova field declaration
require('./field');
