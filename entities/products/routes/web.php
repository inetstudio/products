<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'namespace' => 'InetStudio\ProductsPackage\Products\Contracts\Http\Controllers\Back',
        'middleware' => ['web', 'back.auth'],
        'prefix' => 'back/products-package',
    ],
    function () {
        Route::any(
            'products/data/index',
            'DataControllerContract@getIndexData'
        )->name('back.products-package.products.data.index');

        Route::any(
            'products/data/embedded',
            'DataControllerContract@getEmbeddedData'
        )->name('back.products-package.products.data.embedded');

        Route::any(
            'products/data/modal',
            'DataControllerContract@getModalData'
        )->name('back.products-package.products.data.modal');

        Route::any('products/data/analytics/brands', 'ProductsDataController@dataBrands')->name('back.products.data.analytics.brands');
        Route::any('products/data/analytics/brand/{brand}', 'ProductsDataController@dataBrand')->name('back.products.data.analytics.brand');
        Route::any('products/data/analytics/brand/unlinked/{brand}', 'ProductsDataController@dataBrandUnlinked')->name('back.products.data.analytics.brand.unlinked');

        Route::get('products/analytics', 'ProductsAnalyticsController@getBrandsAnalytics')->name('back.products.analytics');
        Route::get('products/analytics/{brand}', 'ProductsAnalyticsController@getBrandAnalytics')->name('back.products.analytics.brand');

        Route::resource(
            'products', 'ResourceControllerContract', [
                'except' => [
                    'create', 'store',
                ],
                'as' => 'back.products-package',
            ]
        );
    }
);
