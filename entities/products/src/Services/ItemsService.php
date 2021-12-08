<?php

namespace InetStudio\ProductsPackage\Products\Services;

use InetStudio\ProductsPackage\Products\Contracts\Models\ProductModelContract;
use InetStudio\ProductsPackage\Products\Contracts\Services\ItemsServiceContract;

class ItemsService implements ItemsServiceContract
{
    protected ProductModelContract $model;

    public function __construct(ProductModelContract $model)
    {
        $this->model = $model;
    }

    public function getModel(): ProductModelContract
    {
        return $this->model;
    }
}
