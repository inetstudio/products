<?php

namespace InetStudio\ProductsPackage\Products\Http\Controllers\Back;

use InetStudio\AdminPanel\Base\Http\Controllers\Controller;
use InetStudio\ProductsPackage\Products\Contracts\Http\Requests\Back\Resource\EditRequestContract;
use InetStudio\ProductsPackage\Products\Contracts\Http\Requests\Back\Resource\ShowRequestContract;
use InetStudio\ProductsPackage\Products\Contracts\Http\Requests\Back\Resource\IndexRequestContract;
use InetStudio\ProductsPackage\Products\Contracts\Http\Controllers\Back\ResourceControllerContract;
use InetStudio\ProductsPackage\Products\Contracts\Http\Requests\Back\Resource\UpdateRequestContract;
use InetStudio\ProductsPackage\Products\Contracts\Http\Responses\Back\Resource\EditResponseContract;
use InetStudio\ProductsPackage\Products\Contracts\Http\Responses\Back\Resource\ShowResponseContract;
use InetStudio\ProductsPackage\Products\Contracts\Http\Requests\Back\Resource\DestroyRequestContract;
use InetStudio\ProductsPackage\Products\Contracts\Http\Responses\Back\Resource\IndexResponseContract;
use InetStudio\ProductsPackage\Products\Contracts\Http\Responses\Back\Resource\UpdateResponseContract;
use InetStudio\ProductsPackage\Products\Contracts\Http\Responses\Back\Resource\DestroyResponseContract;

class ResourceController extends Controller implements ResourceControllerContract
{
    public function index(IndexRequestContract $request, IndexResponseContract $response): IndexResponseContract
    {
        return $response;
    }

    public function show(ShowRequestContract $request, ShowResponseContract $response): ShowResponseContract
    {
        return $response;
    }

    public function edit(EditRequestContract $request, EditResponseContract $response): EditResponseContract
    {
        return $response;
    }

    public function update(UpdateRequestContract $request, UpdateResponseContract $response): UpdateResponseContract
    {
        return $response;
    }

    public function destroy(DestroyRequestContract $request, DestroyResponseContract $response): DestroyResponseContract
    {
        return $response;
    }
}
