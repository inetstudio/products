<?php

return [
    'images' => [
        'quality' => 75,
        'conversions' => [
            'product_item' => [
                'preview' => [
                    'vertical' => [
                        [
                            'name' => 'preview_vertical',
                            'size' => [
                                'width' => 300,
                                'height' => 400,
                            ],
                        ],
                    ],
                ],
                'content' => [
                    'default' => [
                        [
                            'name' => 'content_admin',
                            'size' => [
                                'width' => 140,
                            ],
                        ],
                        [
                            'name' => 'content_front',
                            'quality' => 70,
                            'fit' => [
                                'width' => 768,
                                'height' => 512,
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'crops' => [
            'product_item' => [
                'preview' => [
                    [
                        'title' => 'Вертикальная ориентация',
                        'name' => 'vertical',
                        'size' => [
                            'width' => 300,
                            'height' => 400,
                            'type' => 'min',
                            'description' => 'Минимальный размер области — 300x400 пикселей'
                        ],
                    ],
                ],
            ],
        ],
        'properties' => [
            'preview' => [
                [
                    'title' => 'Описание',
                    'name' => 'description',
                ],
                [
                    'title' => 'Copyright',
                    'name' => 'copyright',
                ],
                [
                    'title' => 'Alt',
                    'name' => 'alt',
                ]
            ],
        ]
    ],

    'list_styles' => [
        [
            'text' => 'Чек-лист',
            'value' => 'checklist',
        ],
        [
            'text' => 'Нумерованный список',
            'value' => 'numlist',
        ],
        [
            'text' => 'h2',
            'value' => 'h2',
        ],
        [
            'text' => 'h3',
            'value' => 'h3',
        ],
    ],
];
