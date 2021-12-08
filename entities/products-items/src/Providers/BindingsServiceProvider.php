<?php

namespace InetStudio\ProductsPackage\ProductsItems\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class BindingsServiceProvider extends BaseServiceProvider implements DeferrableProvider
{
    public array $bindings = [
        'InetStudio\ProductsPackage\ProductsItems\Contracts\Actions\Back\Resource\DestroyActionContract' => 'InetStudio\ProductsPackage\ProductsItems\Actions\Back\Resource\DestroyAction',
        'InetStudio\ProductsPackage\ProductsItems\Contracts\Actions\Back\Resource\StoreActionContract' => 'InetStudio\ProductsPackage\ProductsItems\Actions\Back\Resource\StoreAction',
        'InetStudio\ProductsPackage\ProductsItems\Contracts\Actions\Back\Resource\UpdateActionContract' => 'InetStudio\ProductsPackage\ProductsItems\Actions\Back\Resource\UpdateAction',

        'InetStudio\ProductsPackage\ProductsItems\Contracts\DTO\Actions\Back\Resource\DestroyItemDataContract' => 'InetStudio\ProductsPackage\ProductsItems\DTO\Actions\Back\Resource\DestroyItemData',
        'InetStudio\ProductsPackage\ProductsItems\Contracts\DTO\Actions\Back\Resource\ShowItemDataContract' => 'InetStudio\ProductsPackage\ProductsItems\DTO\Actions\Back\Resource\ShowItemData',
        'InetStudio\ProductsPackage\ProductsItems\Contracts\DTO\Actions\Back\Resource\StoreItemDataContract' => 'InetStudio\ProductsPackage\ProductsItems\DTO\Actions\Back\Resource\StoreItemData',
        'InetStudio\ProductsPackage\ProductsItems\Contracts\DTO\Actions\Back\Resource\UpdateItemDataContract' => 'InetStudio\ProductsPackage\ProductsItems\DTO\Actions\Back\Resource\UpdateItemData',
        'InetStudio\ProductsPackage\ProductsItems\Contracts\DTO\Queries\FetchItemsByIdsDataContract' => 'InetStudio\ProductsPackage\ProductsItems\DTO\Queries\FetchItemsByIdsData',

        'InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Controllers\Back\ResourceControllerContract' => 'InetStudio\ProductsPackage\ProductsItems\Http\Controllers\Back\ResourceController',

        'InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Requests\Back\Resource\DestroyRequestContract' => 'InetStudio\ProductsPackage\ProductsItems\Http\Requests\Back\Resource\DestroyRequest',
        'InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Requests\Back\Resource\ShowRequestContract' => 'InetStudio\ProductsPackage\ProductsItems\Http\Requests\Back\Resource\ShowRequest',
        'InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Requests\Back\Resource\StoreRequestContract' => 'InetStudio\ProductsPackage\ProductsItems\Http\Requests\Back\Resource\StoreRequest',
        'InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Requests\Back\Resource\UpdateRequestContract' => 'InetStudio\ProductsPackage\ProductsItems\Http\Requests\Back\Resource\UpdateRequest',

        'InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Resources\Back\Resource\DestroyResourceContract' => 'InetStudio\ProductsPackage\ProductsItems\Http\Resources\Back\Resource\DestroyResource',
        'InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Resources\Back\Resource\ShowResourceContract' => 'InetStudio\ProductsPackage\ProductsItems\Http\Resources\Back\Resource\ShowResource',
        'InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Resources\Back\Resource\StoreResourceContract' => 'InetStudio\ProductsPackage\ProductsItems\Http\Resources\Back\Resource\StoreResource',
        'InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Resources\Back\Resource\UpdateResourceContract' => 'InetStudio\ProductsPackage\ProductsItems\Http\Resources\Back\Resource\UpdateResource',

        'InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Responses\Back\Resource\DestroyResponseContract' => 'InetStudio\ProductsPackage\ProductsItems\Http\Responses\Back\Resource\DestroyResponse',
        'InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Responses\Back\Resource\ShowResponseContract' => 'InetStudio\ProductsPackage\ProductsItems\Http\Responses\Back\Resource\ShowResponse',
        'InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Responses\Back\Resource\StoreResponseContract' => 'InetStudio\ProductsPackage\ProductsItems\Http\Responses\Back\Resource\StoreResponse',
        'InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Responses\Back\Resource\UpdateResponseContract' => 'InetStudio\ProductsPackage\ProductsItems\Http\Responses\Back\Resource\UpdateResponse',

        'InetStudio\ProductsPackage\ProductsItems\Contracts\Models\ProductItemModelContract' => 'InetStudio\ProductsPackage\ProductsItems\Models\ProductItemModel',

        'InetStudio\ProductsPackage\ProductsItems\Contracts\Queries\FetchItemsByIdsContract' => 'InetStudio\ProductsPackage\ProductsItems\Queries\FetchItemsByIds'

    ];

    public function provides()
    {
        return array_keys($this->bindings);
    }
}
