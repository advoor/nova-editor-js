<?php

return [
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
                'allowedTags' => 'i,b,u,a[href]'
            ]
        ],
    ]
];
