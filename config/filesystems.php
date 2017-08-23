<?php

return [

    /*
     * Расширение файла конфигурации app/config/filesystems.php
     * добавляет локальные диски для хранения изображений постов и пользователей
     */

    'products' => [
        'driver' => 'local',
        'root' => storage_path('app/public/products/'),
        'url' => env('APP_URL').'/storage/products/',
        'visibility' => 'public',
    ],

];
