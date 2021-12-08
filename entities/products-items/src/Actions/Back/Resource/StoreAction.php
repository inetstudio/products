<?php

namespace InetStudio\ProductsPackage\ProductsItems\Actions\Back\Resource;

use InetStudio\ProductsPackage\ProductsItems\Contracts\Models\ProductItemModelContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Actions\Back\Resource\StoreActionContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\DTO\Actions\Back\Resource\StoreItemDataContract;

class StoreAction implements StoreActionContract
{
    public function __construct(
        protected ProductItemModelContract $model
    ) {}

    public function execute(StoreItemDataContract $data): ?ProductItemModelContract
    {
        $item = new $this->model;

        $item->title = $data->title;
        $item->content = $data->content;

        $item->save();

        return $item;
    }
}
