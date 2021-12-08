<?php

namespace InetStudio\ProductsPackage\Products\Http\Responses\Back\Resource;

use InetStudio\ProductsPackage\Products\Contracts\Services\Back\ResourceServiceContract;
use InetStudio\ProductsPackage\Products\Contracts\Http\Responses\Back\Resource\EditResponseContract;

class EditResponse implements EditResponseContract
{
    protected ResourceServiceContract $resourceService;

    public function __construct(ResourceServiceContract $resourceService)
    {
        $this->resourceService = $resourceService;
    }

    public function toResponse($request)
    {
        $id = $request->route('product', 0);

        $item = $this->resourceService->show($id);

        return response()->view('admin.module.products-package.products::back.pages.form', compact('item'));
    }
}
