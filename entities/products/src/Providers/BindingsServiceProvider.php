<?php

namespace InetStudio\ProductsPackage\Products\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Class BindingsServiceProvider.
 */
class BindingsServiceProvider extends BaseServiceProvider implements DeferrableProvider
{
    /**
     * @var array
     */
    public $bindings = [
        'InetStudio\ProductsPackage\Products\Contracts\Console\Commands\ProcessFeedsCommandContract' => 'InetStudio\ProductsPackage\Products\Console\Commands\ProcessFeedsCommand',
        'InetStudio\ProductsPackage\Products\Contracts\Events\Back\ModifyItemEventContract' => 'InetStudio\ProductsPackage\Products\Events\Back\ModifyItemEvent',
        'InetStudio\ProductsPackage\Products\Contracts\Http\Controllers\Back\ResourceControllerContract' => 'InetStudio\ProductsPackage\Products\Http\Controllers\Back\ResourceController',
        'InetStudio\ProductsPackage\Products\Contracts\Http\Controllers\Back\DataControllerContract' => 'InetStudio\ProductsPackage\Products\Http\Controllers\Back\DataController',
        'InetStudio\ProductsPackage\Products\Contracts\Http\Controllers\Back\UtilityControllerContract' => 'InetStudio\ProductsPackage\Products\Http\Controllers\Back\UtilityController',
        'InetStudio\ProductsPackage\Products\Contracts\Http\Requests\Back\SaveItemRequestContract' => 'InetStudio\ProductsPackage\Products\Http\Requests\Back\SaveItemRequest',
        'InetStudio\ProductsPackage\Products\Contracts\Http\Responses\Back\Resource\DestroyResponseContract' => 'InetStudio\ProductsPackage\Products\Http\Responses\Back\Resource\DestroyResponse',
        'InetStudio\ProductsPackage\Products\Contracts\Http\Responses\Back\Resource\FormResponseContract' => 'InetStudio\ProductsPackage\Products\Http\Responses\Back\Resource\FormResponse',
        'InetStudio\ProductsPackage\Products\Contracts\Http\Responses\Back\Resource\IndexResponseContract' => 'InetStudio\ProductsPackage\Products\Http\Responses\Back\Resource\IndexResponse',
        'InetStudio\ProductsPackage\Products\Contracts\Http\Responses\Back\Resource\SaveResponseContract' => 'InetStudio\ProductsPackage\Products\Http\Responses\Back\Resource\SaveResponse',
        'InetStudio\ProductsPackage\Products\Contracts\Http\Responses\Back\Utility\SuggestionsResponseContract' => 'InetStudio\ProductsPackage\Products\Http\Responses\Back\Utility\SuggestionsResponse',
        'InetStudio\ProductsPackage\Products\Contracts\Managers\FilterServicesManagerContract' => 'InetStudio\ProductsPackage\Products\Managers\FilterServicesManager',
        'InetStudio\ProductsPackage\Products\Contracts\Models\ProductModelContract' => 'InetStudio\ProductsPackage\Products\Models\ProductModel',
        'InetStudio\ProductsPackage\Products\Contracts\Services\Back\DataTables\CardWidgetServiceContract' => 'InetStudio\ProductsPackage\Products\Services\Back\DataTables\CardWidgetService',
        'InetStudio\ProductsPackage\Products\Contracts\Services\Back\DataTables\IndexServiceContract' => 'InetStudio\ProductsPackage\Products\Services\Back\DataTables\IndexService',
        'InetStudio\ProductsPackage\Products\Contracts\Services\Back\ItemsServiceContract' => 'InetStudio\ProductsPackage\Products\Services\Back\ItemsService',
        'InetStudio\ProductsPackage\Products\Contracts\Services\Back\UtilityServiceContract' => 'InetStudio\ProductsPackage\Products\Services\Back\UtilityService',
        'InetStudio\ProductsPackage\Products\Contracts\Services\Common\Filter\BuilderFilterServiceContract' => 'InetStudio\ProductsPackage\Products\Services\Common\Filter\BuilderFilterService',
        'InetStudio\ProductsPackage\Products\Contracts\Services\Common\Filter\ModelFilterServiceContract' => 'InetStudio\ProductsPackage\Products\Services\Common\Filter\ModelFilterService',
        'InetStudio\ProductsPackage\Products\Contracts\Services\Front\FeedsServiceContract' => 'InetStudio\ProductsPackage\Products\Services\Front\FeedsService',
        'InetStudio\ProductsPackage\Products\Contracts\Services\Front\ItemsServiceContract' => 'InetStudio\ProductsPackage\Products\Services\Front\ItemsService',
        'InetStudio\ProductsPackage\Products\Contracts\Services\Front\SitemapServiceContract' => 'InetStudio\ProductsPackage\Products\Services\Front\SitemapService',
        'InetStudio\ProductsPackage\Products\Contracts\Transformers\Back\DataTables\CardWidgetTransformerContract' => 'InetStudio\ProductsPackage\Products\Transformers\Back\DataTables\CardWidgetTransformer',
        'InetStudio\ProductsPackage\Products\Contracts\Transformers\Back\DataTables\IndexTransformerContract' => 'InetStudio\ProductsPackage\Products\Transformers\Back\DataTables\IndexTransformer',
        'InetStudio\ProductsPackage\Products\Contracts\Transformers\Back\Utility\SuggestionTransformerContract' => 'InetStudio\ProductsPackage\Products\Transformers\Back\Utility\SuggestionTransformer',
        'InetStudio\ProductsPackage\Products\Contracts\Transformers\Front\Sitemap\ItemTransformerContract' => 'InetStudio\ProductsPackage\Products\Transformers\Front\Sitemap\ItemTransformer',
    ];

    /**
     * Получить сервисы от провайдера.
     *
     * @return array
     */
    public function provides()
    {
        return array_keys($this->bindings);
    }
}
