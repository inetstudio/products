<?php

namespace InetStudio\ProductsPackage\Products\Contracts\Services\Back;

use InetStudio\ProductsPackage\Products\Contracts\Models\ProductModelContract;
use InetStudio\ProductsPackage\Products\Contracts\Services\ItemsServiceContract as BaseItemsServiceContract;

interface ResourceServiceContract extends BaseItemsServiceContract
{
    public function show(int|string $id): ProductModelContract;

    public function destroy(int|string $id): int;
}
