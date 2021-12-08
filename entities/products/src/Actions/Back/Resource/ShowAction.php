<?php

namespace InetStudio\ProductsPackage\Products\Actions\Back\Resource;

use InetStudio\ProductsPackage\Products\Contracts\Models\ProductModelContract;
use InetStudio\ProductsPackage\Products\Contracts\DTO\Back\Resource\ShowItemDataContract;
use InetStudio\ProductsPackage\Products\Contracts\Actions\Back\Resource\ShowActionContract;

class ShowAction implements ShowActionContract
{
    public function __construct(
        protected ProductModelContract $model
    ) {}

    public function execute(?ShowItemDataContract $data = null): ?ProductModelContract
    {
        if (! $data) {
            return null;
        }

        return $this->model::find($data->id);
    }
}
