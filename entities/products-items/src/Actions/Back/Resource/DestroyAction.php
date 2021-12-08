<?php

namespace InetStudio\ProductsPackage\ProductsItems\Actions\Back\Resource;

use InetStudio\ProductsPackage\ProductsItems\Contracts\Models\ProductItemModelContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Actions\Back\Resource\DestroyActionContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\DTO\Actions\Back\Resource\DestroyItemDataContract;

class DestroyAction implements DestroyActionContract
{
    public function __construct(
        protected ProductItemModelContract $model
    ) {}

    public function execute(DestroyItemDataContract $data): int
    {
        return $this->model::destroy($data->id);
    }
}
