<?php

namespace InetStudio\ProductsPackage\Products\Http\Responses\Back\Resource;

use InetStudio\ProductsPackage\Products\Contracts\Services\Back\ResourceServiceContract;
use InetStudio\ProductsPackage\Products\Contracts\Http\Responses\Back\Resource\ShowResponseContract;

class ShowResponse implements ShowResponseContract
{
    protected ResourceServiceContract $resourceService;

    public function __construct(ResourceServiceContract $resourceService)
    {
        $this->resourceService = $resourceService;
    }

    public function toResponse($request)
    {
        $id = $request->route('product');

        $item = $this->resourceService->show($id);

        return response()->json($item->toArray());
    }
}
