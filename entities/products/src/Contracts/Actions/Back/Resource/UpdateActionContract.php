<?php

namespace InetStudio\ProductsPackage\Products\Contracts\Actions\Back\Resource;

use InetStudio\ProductsPackage\Products\Contracts\Models\ProductModelContract;
use InetStudio\ProductsPackage\Products\Contracts\DTO\Back\Resource\UpdateItemDataContract;

interface UpdateActionContract
{
    public function execute(?UpdateItemDataContract $data = null): ?ProductModelContract;
}
