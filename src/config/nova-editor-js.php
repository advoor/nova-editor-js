<?php

return [
    /**
     * Configure tools
     */
    'toolSettings' => [
        'header' => [
            'activated' => true,
            'placeholder' => 'Heading',
            'shortcut' => 'CMD+SHIFT+H'
        ],
        'list' => [
            'activated' => true,
            'inlineToolbar' => true,
            'shortcut' => 'CMD+SHIFT+L'
        ],
        'code' => [
            'activated' => true,
            'placeholder' => '',
            'shortcut' => 'CMD+SHIFT+C'
        ],
        'link' => [
            'activated' => true,
            'shortcut' => 'CMD+SHIFT+L'
        ],
        'image' => [
            'activated' => true,
            'shortcut' => 'CMD+SHIFT+I',
            'path' => 'public/images',
            'disk' => 'local',
            'alterations' => [
                'resize' => [
                    'width' => false, // integer
                    'height' => false, // integer
                ],
                'optimize' => true, // true or false
                'adjustments' => [
                    'brightness' => false, // -100 to 100
                    'contrast' => false, // -100 to 100
                    'gamma' => false // 0.1 to 9.99
                ],
                'effects' => [
                    'blur' => false, // 0 to 100
                    'pixelate' => false, // 0 to 100
                    'greyscale' => false, // true or false
                    'sepia' => false, // true or false
                    'sharpen' => false, // 0 to 100
                ]
            ],
            'thumbnails' => [
                // Specify as many thumbnails as required. Key is used as the name.
                '_small' => [
                    'resize' => [
                        'width' => 250, // integer
                        'height' => 250, // integer
                    ],
                    'optimize' => true, // true or false
                    'adjustments' => [
                        'brightness' => false, // -100 to 100
                        'contrast' => false, // -100 to 100
                        'gamma' => false // 0.1 to 9.99
                    ],
                    'effects' => [
                        'blur' => false, // 0 to 100
                        'pixelate' => false, // 0 to 100
                        'greyscale' => false, // true or false
                        'sepia' => false, // true or false
                        'sharpen' => false, // 0 to 100
                    ]
                ]
            ]
        ],
        'inlineCode' => [
            'activated' => true,
            'shortcut' => 'CMD+SHIFT+A',
        ],
        'checklist' => [
            'activated' => true,
            'inlineToolbar' => true,
            'shortcut' => 'CMD+SHIFT+J',
        ],
        'marker' => [
            'activated' => true,
            'shortcut' => 'CMD+SHIFT+M',
        ],
        'delimiter' => [
            'activated' => true,
        ],
        'table' => [
            'activated' => true,
            'inlineToolbar' => true,
        ],
        'raw' => [
            'activated' => true,
            'placeholder' => '',
        ],
        'embed' => [
            'activated' => true,
            'inlineToolbar' => true,
            'services' => [
                'codepen' => true,
                'imgur' => false,
                'vimeo' => true,
                'youtube' => true
            ],
        ],
    ],

    /**
     * Output validation config
     * https://github.com/editor-js/editorjs-php
     */
    'validationSettings' => [
        'tools' => [
            'header' => [
                'text' => [
                    'type' => 'string',
                ],
                'level' => [
                    'type' => 'int',
                    'canBeOnly' => [1, 2, 3, 4, 5]
                ]
            ],
            'paragraph' => [
                'text' => [
                    'type' => 'string',
                    'allowedTags' => 'i,b,u,a[href],span[class],code[class],mark[class]'
                ]
            ],
            'list' => [
                'style' => [
                    'type' => 'string',
                    'canBeOnly' =>
                        [
                            0 => 'ordered',
                            1 => 'unordered',
                        ],
                ],
                'items' => [
                    'type' => 'array',
                    'data' => [
                        '-' => [
                            'type' => 'string',
                            'allowedTags' => 'i,b,u',
                        ],
                    ],
                ],
            ],
            'image' => [
                'file' => [
                    'type' => 'array',
                    'data' => [
                        'url' => [
                            'type' => 'string',
                        ],
                        'thumbnails' => [
                            'type' => 'array',
                            'required' => false,
                            'data' => [
                                '-' => [
                                    'type' => 'string',
                                ]
                            ],
                        ]
                    ],
                ],
                'caption' => [
                    'type' => 'string'
                ],
                'withBorder' => [
                    'type' => 'boolean'
                ],
                'withBackground' => [
                    'type' => 'boolean'
                ],
                'stretched' => [
                    'type' => 'boolean'
                ]
            ],
            'code' => [
                'code' => [
                    'type' => 'string'
                ]
            ],
            'linkTool' => [
                'link' => [
                    'type' => 'string'
                ],
                'meta' => [
                    'type' => 'array',
                    'data' => [
                        'title' => [
                            'type' => 'string',
                        ],
                        'description' => [
                            'type' => 'string',
                        ],
                        'image' => [
                            'type' => 'array',
                            'data' => [
                                'url' => [
                                    'type' => 'string',
                                ],
                            ]
                        ]
                    ]
                ]
            ],
            'checklist' => [
                'items' => [
                    'type' => 'array',
                    'data' => [
                        '-' => [
                            'type' => 'array',
                            'data' => [
                                'text' => [
                                    'type' => 'string',
                                    'required' => false
                                ],
                                'checked' => [
                                    'type' => 'boolean',
                                    'required' => false
                                ],
                            ],

                        ],
                    ],
                ],
            ],
            'delimiter' => [

            ],
            'table' => [
                'content' => [
                    'type' => 'array',
                    'data' => [
                        '-' => [
                            'type' => 'array',
                            'data' => [
                                '-' => [
                                    'type' => 'string',
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'raw' => [
                'html' => [
                    'type' => 'string',
                    'allowedTags' => '*',
                ]
            ],
            'embed' => [
                'service' => [
                    'type' => 'string'
                ],
                'source' => [
                    'type' => 'string'
                ],
                'embed' => [
                    'type' => 'string'
                ],
                'width' => [
                    'type' => 'int'
                ],
                'height' => [
                    'type' => 'int'
                ],
                'caption' => [
                    'type' => 'string',
                    'required' => false,
                ],
            ]
        ]
    ]
];
