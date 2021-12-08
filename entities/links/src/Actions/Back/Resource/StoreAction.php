<?php

namespace InetStudio\ProductsPackage\Links\Actions\Back\Resource;

use InetStudio\ProductsPackage\Links\Contracts\Models\LinkModelContract;
use InetStudio\ProductsPackage\Links\Contracts\DTO\Back\Resource\StoreItemDataContract;

class StoreAction
{
    public function __construct(
        protected LinkModelContract $model
    ) {}

    public function execute(StoreItemDataContract $data): LinkModelContract
    {
        $item = new $this->model;

        $item->product_id = $data->productId;
        $item->href = $data->href;

        $item->save();

        return $item;
    }
}
