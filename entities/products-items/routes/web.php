<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'namespace' => 'InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Controllers\Back',
        'middleware' => ['web', 'back.auth'],
        'prefix' => 'back/products-package',
    ],
    function () {
        Route::resource(
            'products-items',
            'ResourceControllerContract',
            [
                'except' => [
                    'index', 'create', 'edit',
                ],
                'as' => 'back.products-package',
            ]
        );
    }
);
