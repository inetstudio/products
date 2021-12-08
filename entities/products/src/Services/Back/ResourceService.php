<?php

namespace InetStudio\ProductsPackage\Products\Services\Back;

use InetStudio\ProductsPackage\Products\Contracts\Models\ProductModelContract;
use InetStudio\ProductsPackage\Products\Services\ItemsService as BaseItemsService;
use InetStudio\ProductsPackage\Products\Contracts\Services\Back\ResourceServiceContract;

class ResourceService extends BaseItemsService implements ResourceServiceContract
{
    public function show(int|string $id): ProductModelContract
    {
        return $this->model::find($id);
    }

    public function destroy(int|string $id): int
    {
        return $this->model::destroy($id);
    }
}
