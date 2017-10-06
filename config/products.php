<?php

return [

    /*
     * Настройки таблиц
     */

    'datatables' => [
        'ajax' => [
            'index' => [
                'url' => 'back.products.data',
                'type' => 'POST',
                'data' => 'function(data) { data._token = $(\'meta[name="csrf-token"]\').attr(\'content\'); }',
            ],
            'embedded' => [
                'url' => 'back.products.data',
                'url_params' => [
                    'type' => 'embedded',
                ],
                'type' => 'POST',
                'data' => 'function(data) { data._token = $(\'meta[name="csrf-token"]\').attr(\'content\'); }',
            ],
        ],
        'table' => [
            'default' => [
                'paging' => true,
                'pagingType' => 'full_numbers',
                'searching' => true,
                'info' => false,
                'searchDelay' => 350,
                'language' => [
                    'url' => '/admin/js/plugins/datatables/locales/russian.json',
                ],
            ],
        ],
        'columns' => [
            'index' => [
                ['data' => 'preview', 'name' => 'preview', 'title' => 'Изображение', 'orderable' => false, 'searchable' => false],
                ['data' => 'title', 'name' => 'title', 'title' => 'Название'],
                ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Дата создания'],
                ['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Дата обновления'],
                ['data' => 'actions', 'name' => 'actions', 'title' => 'Действия', 'orderable' => false, 'searchable' => false],
            ],
            'embedded' => [
                ['data' => 'id', 'name' => 'id', 'title' => 'ID', 'orderable' => false, 'searchable' => false, 'visible' => false],
                ['data' => 'preview', 'name' => 'preview', 'title' => 'Изображение', 'orderable' => false, 'searchable' => false],
                ['data' => 'brand', 'name' => 'brand', 'title' => 'Бренд'],
                ['data' => 'title', 'name' => 'title', 'title' => 'Название'],
                ['data' => 'description', 'name' => 'description', 'title' => 'Описание'],
                ['data' => 'actions', 'name' => 'actions', 'title' => 'Действия', 'orderable' => false, 'searchable' => false],
            ],
        ],
    ],

    /*
     * Ссылки на продуктовые feed'ы
     */

    'feeds' => [
        'google' => [

        ],
        'yandex' => [

        ],
    ],

    'images' => [
        'quality' => 75,
        'conversions' => [
            'preview' => [
                'default' => [
                    [
                        'name' => 'preview_default',
                        'size' => [
                            'width' => 260,
                            'height' => 287,
                        ],
                    ],
                    [
                        'name' => 'preview_admin',
                        'size' => [
                            'width' => 128,
                            'height' => 128,
                        ],
                    ],
                ],
            ],
        ]
    ],
];
