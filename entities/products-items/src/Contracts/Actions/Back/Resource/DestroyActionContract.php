<?php

namespace InetStudio\ProductsPackage\ProductsItems\Contracts\Actions\Back\Resource;

use InetStudio\ProductsPackage\ProductsItems\Contracts\DTO\Actions\Back\Resource\DestroyItemDataContract;

interface DestroyActionContract
{
    public function execute(DestroyItemDataContract $data): int;
}
