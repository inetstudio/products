<?php

namespace InetStudio\ProductsPackage\Products\Http\Responses\Back\Resource;

use InetStudio\ProductsPackage\Products\Contracts\Services\Back\ResourceServiceContract;
use InetStudio\ProductsPackage\Products\Contracts\Http\Responses\Back\Resource\DestroyResponseContract;

class DestroyResponse implements DestroyResponseContract
{
    protected ResourceServiceContract $resourceService;

    public function __construct(ResourceServiceContract $resourceService)
    {
        $this->resourceService = $resourceService;
    }

    public function toResponse($request)
    {
        $id = $request->route('product');

        $count = $this->resourceService->destroy($id);

        return response()->json(
            [
                'success' => ($count > 0),
            ]
        );
    }
}
