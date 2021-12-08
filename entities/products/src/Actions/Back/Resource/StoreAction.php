<?php

namespace InetStudio\ProductsPackage\Products\Actions\Back\Resource;

use InetStudio\ProductsPackage\Products\Contracts\Models\ProductModelContract;
use InetStudio\ProductsPackage\Products\Contracts\DTO\Back\Resource\StoreItemDataContract;
use InetStudio\ProductsPackage\Products\Contracts\Actions\Back\Resource\StoreActionContract;

class StoreAction implements StoreActionContract
{
    public function __construct(
        protected ProductModelContract $model
    ) {}

    public function execute(?StoreItemDataContract $data = null): ?ProductModelContract
    {
        if (! $data) {
            return null;
        }

        $item = new $this->model;

        $item->title = $data->title;
        $item->content = $data->content;

        $item->save();

        return $item;
    }
}
