<?php

namespace InetStudio\ProductsPackage\Products\Contracts\Services;

use InetStudio\ProductsPackage\Products\Contracts\Models\ProductModelContract;

interface ItemsServiceContract
{
    public function getModel(): ProductModelContract;
}
