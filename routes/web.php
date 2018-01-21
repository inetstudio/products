<?php

Route::group(['namespace' => 'InetStudio\Products\Http\Controllers\Back'], function () {
    Route::group(['middleware' => 'web', 'prefix' => 'back'], function () {
        Route::group(['middleware' => 'back.auth'], function () {
            Route::get('products/analytics', 'ProductsController@getAnalytics')->name('back.products.analytics');
            Route::get('products/analytics/{brand}', 'ProductsController@getBrandAnalytics')->name('back.products.analytics.brand');
            Route::any('products/data/{type?}', 'ProductsDataController@data')->name('back.products.data');
            Route::any('products/data/analytics/brand/', 'ProductsDataController@dataBrand')->name('back.products.data.analytics.brand');
            Route::any('products/data/analytics/brand/unlinked/', 'ProductsDataController@dataBrandUnlinked')->name('back.products.data.analytics.brand.unlinked');
            Route::resource('products', 'ProductsController', ['only' => [
                'index', 'edit', 'destroy',
            ], 'as' => 'back']);
        });
    });
});
