<?php

Route::group([
    'namespace' => 'InetStudio\Products\Contracts\Http\Controllers\Back',
    'middleware' => ['web', 'back.auth'],
    'prefix' => 'back',
], function () {
    Route::any('products/data', 'ProductsDataControllerContract@data')->name('back.products.data.index');
    Route::any('products/data/embedded', 'ProductsDataControllerContract@dataEmbedded')->name('back.products.data.embedded');
    Route::any('products/data/modal', 'ProductsDataControllerContract@dataModal')->name('back.products.data.modal');

    Route::resource('products', 'ProductsControllerContract', ['except' => [
        'create', 'store',
    ], 'as' => 'back']);
});

Route::group([
    'namespace' => 'InetStudio\Products\Http\Controllers\Back',
    'middleware' => ['web', 'back.auth'],
    'prefix' => 'back',
], function () {
    Route::get('products/analytics', 'ProductsAnalyticsController@getBrandsAnalytics')->name('back.products.analytics');
    Route::get('products/analytics/{brand}', 'ProductsAnalyticsController@getBrandAnalytics')->name('back.products.analytics.brand');

    Route::any('products/data/analytics/brands', 'ProductsDataController@dataBrands')->name('back.products.data.analytics.brands');
    Route::any('products/data/analytics/brand/{brand}', 'ProductsDataController@dataBrand')->name('back.products.data.analytics.brand');
    Route::any('products/data/analytics/brand/unlinked/{brand}', 'ProductsDataController@dataBrandUnlinked')->name('back.products.data.analytics.brand.unlinked');
});

Route::group([
    'namespace' => 'InetStudio\Products\Contracts\Http\Controllers\Back',
    'middleware' => ['web', 'back.auth'],
    'prefix' => 'back/products',
], function () {
    Route::resource('items', 'ProductsItemsControllerContract', ['only' => [
        'show', 'store', 'update', 'destroy',
    ], 'as' => 'back.products']);
});
