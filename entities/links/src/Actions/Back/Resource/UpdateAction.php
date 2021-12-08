<?php

namespace InetStudio\ProductsPackage\Links\Actions\Back\Resource;

use InetStudio\ProductsPackage\Links\Contracts\Models\LinkModelContract;
use InetStudio\ProductsPackage\Links\Contracts\DTO\Back\Resource\UpdateItemDataContract;

class UpdateAction
{
    public function __construct(
        protected LinkModelContract $model
    ) {}

    public function execute(UpdateItemDataContract $data): ?LinkModelContract
    {
        $item = $this->model::find($data->id);

        if (! $item) {
            return null;
        }

        $item->product_id = $data->productId;
        $item->href = $data->href;

        $item->save();

        return $item;
    }
}
