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
            'disk' => 'local'
        ],
        'inlineCode' => [
            'activated' => true,
            'shortcut' => 'CMD+SHIFT+M',
        ],
        'checklist' => [
            'activated' => true,
            'inlineToolbar' => true,
            'shortcut' => 'CMD+SHIFT+J',
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
                    'allowedTags' => 'i,b,u,a[href],span[class],code[class]'
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
        ]
    ]
];
