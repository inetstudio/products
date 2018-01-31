<?php

return [

    'analytics_start_period' => '2017-09-01 00:00',

    /*
     * Настройки таблиц
     */

    'datatables' => [
        'ajax' => [
            'index' => [
                'url' => 'back.products.data',
                'type' => 'POST',
                'data' => 'function(data) { 
                    data._token = $(\'meta[name="csrf-token"]\').attr(\'content\');  
                }',
            ],
            'embedded' => [
                'url' => 'back.products.data',
                'url_params' => [
                    'type' => 'embedded',
                ],
                'type' => 'POST',
                'data' => 'function(data) { 
                    data._token = $(\'meta[name="csrf-token"]\').attr(\'content\');  
                }',
            ],
            'brands' => [
                'url' => 'back.products.data.analytics.brands',
                'type' => 'POST',
                'data' => 'function(data) { 
                    data._token = $(\'meta[name="csrf-token"]\').attr(\'content\');
                    data.startPeriod = $(\'input[name=startPeriod]\').val(); 
                    data.endPeriod = $(\'input[name=endPeriod]\').val(); 
                }',
            ],
            'brand' => [
                'data' => 'function(data) { 
                    data._token = $(\'meta[name="csrf-token"]\').attr(\'content\');  
                }',
            ],
            'brand_unlinked' => [
                'data' => 'function(data) { 
                    data._token = $(\'meta[name="csrf-token"]\').attr(\'content\');  
                }',
            ],
        ],
        'table' => [
            'default' => [
                'paging' => true,
                'pagingType' => 'full_numbers',
                'searching' => true,
                'info' => true,
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
            'brands' => [
                ['data' => 'brand', 'name' => 'brand', 'title' => 'Бренд'],
                ['data' => 'references', 'name' => 'references', 'title' => 'Количество упоминаний'],
                ['data' => 'percents', 'name' => 'percents', 'title' => '%'],
                ['data' => 'views', 'name' => 'views', 'title' => 'Просмотры'],
                ['data' => 'views_users', 'name' => 'views_users', 'title' => 'Пользователи'],
                ['data' => 'clicks', 'name' => 'clicks', 'title' => 'Клики'],
                ['data' => 'clicks_users', 'name' => 'clicks_users', 'title' => 'Пользователи'],
                ['data' => 'conversion', 'name' => 'conversion', 'title' => 'Конверсия %'],
                ['data' => 'shops', 'name' => 'shops', 'title' => 'Магазины'],
            ],
            'brand' => [
                ['data' => 'preview', 'name' => 'preview', 'title' => 'Изображение', 'orderable' => false, 'searchable' => false],
                ['data' => 'title', 'name' => 'title', 'title' => 'Название'],
                ['data' => 'material_type', 'name' => 'material_type', 'title' => 'Тип материала'],
                ['data' => 'material_title', 'name' => 'material_title', 'title' => 'Название материала'],
                ['data' => 'actions', 'name' => 'actions', 'title' => 'Действия', 'orderable' => false, 'searchable' => false],
            ],
            'brand_unlinked' => [
                ['data' => 'preview', 'name' => 'preview', 'title' => 'Изображение', 'orderable' => false, 'searchable' => false],
                ['data' => 'title', 'name' => 'title', 'title' => 'Название'],
                ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Дата создания'],
                ['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Дата обновления'],
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
        ],
    ],
];
