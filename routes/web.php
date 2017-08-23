<?php

Route::group(['namespace' => 'InetStudio\Products\Controllers'], function () {
    Route::group(['middleware' => 'web', 'prefix' => 'back'], function () {
        Route::group(['middleware' => 'back.auth'], function () {
            Route::any('products/data/{type?}', 'ProductsController@data')->name('back.products.data');
            Route::resource('products', 'ProductsController', ['only' => [
                'index', 'edit', 'destroy'
            ], 'as' => 'back']);
        });
    });
});
