<?php

namespace InetStudio\ProductsPackage\ProductsItems\Http\Controllers\Back;

use InetStudio\AdminPanel\Base\Http\Controllers\Controller;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Queries\FetchItemsByIdsContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Actions\Back\Resource\StoreActionContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Actions\Back\Resource\UpdateActionContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Actions\Back\Resource\DestroyActionContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Requests\Back\Resource\ShowRequestContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Requests\Back\Resource\StoreRequestContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Controllers\Back\ResourceControllerContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Requests\Back\Resource\UpdateRequestContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Responses\Back\Resource\ShowResponseContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Responses\Back\Resource\StoreResponseContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Requests\Back\Resource\DestroyRequestContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Responses\Back\Resource\UpdateResponseContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Responses\Back\Resource\DestroyResponseContract;

class ResourceController extends Controller implements ResourceControllerContract
{
    public function show(ShowRequestContract $request, FetchItemsByIdsContract $query, ShowResponseContract $response): ShowResponseContract
    {
        return $this->process($request, $query, $response);
    }

    public function store(StoreRequestContract $request, StoreActionContract $action, StoreResponseContract $response): StoreResponseContract
    {
        return $this->process($request, $action, $response);
    }

    public function update(UpdateRequestContract $request, UpdateActionContract $action, UpdateResponseContract $response): UpdateResponseContract
    {
        return $this->process($request, $action, $response);
    }

    public function destroy(DestroyRequestContract $request, DestroyActionContract $action, DestroyResponseContract $response): DestroyResponseContract
    {
        return $this->process($request, $action, $response);
    }
}
