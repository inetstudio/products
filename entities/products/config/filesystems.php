<?php

return [

    'products' => [
        'driver' => 'local',
        'root' => storage_path('app/public/products'),
        'url' => env('APP_URL').'/storage/products',
        'visibility' => 'public',
    ],

];
