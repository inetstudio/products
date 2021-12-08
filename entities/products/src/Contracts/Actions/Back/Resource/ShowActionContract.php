<?php

namespace InetStudio\ProductsPackage\Products\Contracts\Actions\Back\Resource;

use InetStudio\ProductsPackage\Products\Contracts\Models\ProductModelContract;
use InetStudio\ProductsPackage\Products\Contracts\DTO\Back\Resource\ShowItemDataContract;

interface ShowActionContract
{
    public function execute(?ShowItemDataContract $data = null): ?ProductModelContract;
}
