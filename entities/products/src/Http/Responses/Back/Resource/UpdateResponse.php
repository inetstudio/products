<?php

namespace InetStudio\ProductsPackage\Products\Http\Responses\Back\Resource;

use Illuminate\Support\Facades\Session;
use InetStudio\ProductsPackage\Products\DTO\Back\Resource\Save\ItemData;
use InetStudio\ProductsPackage\Products\Contracts\Services\Back\ResourceServiceContract;
use InetStudio\ProductsPackage\Products\Contracts\Http\Responses\Back\Resource\UpdateResponseContract;

class UpdateResponse implements UpdateResponseContract
{
    protected ResourceServiceContract $resourceService;

    public function __construct(ResourceServiceContract $resourceService)
    {
        $this->resourceService = $resourceService;
    }

    public function toResponse($request)
    {
        $data = ItemData::fromRequest($request);

        $item = $this->resourceService->save($data);

        Session::flash('success', 'Продукт «'.$item['name'].'» успешно обновлен');

        return response()->redirectToRoute('back.catalog-package.products.edit', $item['id']);
    }
}
