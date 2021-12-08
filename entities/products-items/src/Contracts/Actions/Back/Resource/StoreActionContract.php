<?php

namespace InetStudio\ProductsPackage\ProductsItems\Contracts\Actions\Back\Resource;

use InetStudio\ProductsPackage\ProductsItems\Contracts\Models\ProductItemModelContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\DTO\Actions\Back\Resource\StoreItemDataContract;

interface StoreActionContract
{
    public function execute(StoreItemDataContract $data): ?ProductItemModelContract;
}
