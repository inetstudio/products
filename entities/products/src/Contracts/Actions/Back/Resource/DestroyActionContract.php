<?php

namespace InetStudio\ProductsPackage\Products\Contracts\Actions\Back\Resource;

use InetStudio\ProductsPackage\Products\Contracts\DTO\Back\Resource\DestroyItemDataContract;

interface DestroyActionContract
{
    public function execute(?DestroyItemDataContract $data = null): int;
}
