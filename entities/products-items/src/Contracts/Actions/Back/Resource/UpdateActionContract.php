<?php

namespace InetStudio\ProductsPackage\ProductsItems\Contracts\Actions\Back\Resource;

use InetStudio\ProductsPackage\ProductsItems\Contracts\Models\ProductItemModelContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\DTO\Actions\Back\Resource\UpdateItemDataContract;

interface UpdateActionContract
{
    public function execute(UpdateItemDataContract $data): ?ProductItemModelContract;
}
