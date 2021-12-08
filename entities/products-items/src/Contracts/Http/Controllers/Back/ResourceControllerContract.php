<?php

namespace InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Controllers\Back;

use InetStudio\ProductsPackage\ProductsItems\Contracts\Queries\FetchItemsByIdsContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Actions\Back\Resource\StoreActionContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Actions\Back\Resource\UpdateActionContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Actions\Back\Resource\DestroyActionContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Requests\Back\Resource\ShowRequestContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Requests\Back\Resource\StoreRequestContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Responses\Back\Resource\ShowResponseContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Requests\Back\Resource\UpdateRequestContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Responses\Back\Resource\StoreResponseContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Requests\Back\Resource\DestroyRequestContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Responses\Back\Resource\UpdateResponseContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Responses\Back\Resource\DestroyResponseContract;

interface ResourceControllerContract
{
    public function show(ShowRequestContract $request, FetchItemsByIdsContract $query, ShowResponseContract $response): ShowResponseContract;

    public function store(StoreRequestContract $request, StoreActionContract $action, StoreResponseContract $response): StoreResponseContract;

    public function update(UpdateRequestContract $request, UpdateActionContract $action, UpdateResponseContract $response): UpdateResponseContract;

    public function destroy(DestroyRequestContract $request, DestroyActionContract $action, DestroyResponseContract $response): DestroyResponseContract;
}
