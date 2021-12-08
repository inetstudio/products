<?php

namespace InetStudio\ProductsPackage\Products\Actions\Back\Resource;

use InetStudio\ProductsPackage\Products\Contracts\Models\ProductModelContract;
use InetStudio\ProductsPackage\Products\Contracts\DTO\Back\Resource\DestroyItemDataContract;
use InetStudio\ProductsPackage\Products\Contracts\Actions\Back\Resource\DestroyActionContract;

class DestroyAction implements DestroyActionContract
{
    public function __construct(
        protected ProductModelContract $model
    ) {}

    public function execute(?DestroyItemDataContract $data = null): int
    {
        if (! $data) {
            return 0;
        }

        return $this->model::destroy($data->id);
    }
}
