<?php

namespace InetStudio\Products\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class ProductsBindingsServiceProvider.
 */
class ProductsBindingsServiceProvider extends ServiceProvider
{
    /**
    * @var  bool
    */
    protected $defer = true;

    /**
    * @var  array
    */
    public $bindings = [
        'InetStudio\Products\Contracts\Events\Back\ModifyProductEventContract' => 'InetStudio\Products\Events\Back\ModifyProductEvent',
        'InetStudio\Products\Contracts\Http\Controllers\Back\ProductsDataControllerContract' => 'InetStudio\Products\Http\Controllers\Back\ProductsDataController',
        'InetStudio\Products\Contracts\Http\Controllers\Back\ProductsItemsControllerContract' => 'InetStudio\Products\Http\Controllers\Back\ProductsItemsController',
        'InetStudio\Products\Contracts\Http\Requests\Back\SaveProductItemRequestContract' => 'InetStudio\Products\Http\Requests\Back\SaveProductItemRequest',
        'InetStudio\Products\Contracts\Http\Responses\Back\ProductsItems\DestroyResponseContract' => 'InetStudio\Products\Http\Responses\Back\ProductsItems\DestroyResponse',
        'InetStudio\Products\Contracts\Http\Responses\Back\ProductsItems\SaveResponseContract' => 'InetStudio\Products\Http\Responses\Back\ProductsItems\SaveResponse',
        'InetStudio\Products\Contracts\Http\Responses\Back\ProductsItems\ShowResponseContract' => 'InetStudio\Products\Http\Responses\Back\ProductsItems\ShowResponse',
        'InetStudio\Products\Contracts\Models\ProductItemModelContract' => 'InetStudio\Products\Models\ProductItemModel',
        'InetStudio\Products\Contracts\Models\ProductModelContract' => 'InetStudio\Products\Models\ProductModel',
        'InetStudio\Products\Contracts\Repositories\ProductsItemsRepositoryContract' => 'InetStudio\Products\Repositories\ProductsItemsRepository',
        'InetStudio\Products\Contracts\Repositories\ProductsRepositoryContract' => 'InetStudio\Products\Repositories\ProductsRepository',
        'InetStudio\Products\Contracts\Services\Back\EmbeddedProductsDataTableServiceContract' => 'InetStudio\Products\Services\Back\EmbeddedProductsDataTableService',
        'InetStudio\Products\Contracts\Services\Back\ModalProductsDataTableServiceContract' => 'InetStudio\Products\Services\Back\ModalProductsDataTableService',
        'InetStudio\Products\Contracts\Services\Back\ProductsItemsServiceContract' => 'InetStudio\Products\Services\Back\ProductsItemsService',
        'InetStudio\Products\Contracts\Services\Back\ProductsServiceContract' => 'InetStudio\Products\Services\Back\ProductsService',
        'InetStudio\Products\Contracts\Services\Front\ProductsItemsServiceContract' => 'InetStudio\Products\Services\Front\ProductsItemsService',
        'InetStudio\Products\Contracts\Services\Front\ProductsServiceContract' => 'InetStudio\Products\Services\Front\ProductsService',
        'InetStudio\Products\Contracts\Services\ProductsAnalyticsServiceContract' => 'InetStudio\Products\Services\Back\ProductsAnalyticsService',
        'InetStudio\Products\Contracts\Transformers\Back\EmbeddedProductTransformerContract' => 'InetStudio\Products\Transformers\Back\EmbeddedProductTransformer',
        'InetStudio\Products\Contracts\Transformers\Back\ModalProductTransformerContract' => 'InetStudio\Products\Transformers\Back\ModalProductTransformer',
    ];

    /**
     * Получить сервисы от провайдера.
     *
     * @return  array
     */
    public function provides()
    {
        return array_keys($this->bindings);
    }
}
