<?php

namespace InetStudio\ProductsPackage\Products\Actions\Back\Resource;

use InetStudio\ProductsPackage\Products\Contracts\Models\ProductModelContract;
use InetStudio\ProductsPackage\Products\Contracts\DTO\Back\Resource\UpdateItemDataContract;
use InetStudio\ProductsPackage\Products\Contracts\Actions\Back\Resource\UpdateActionContract;

class UpdateAction implements UpdateActionContract
{
    public function __construct(
        protected ProductModelContract $model
    ) {}

    public function execute(?UpdateItemDataContract $data = null): ?ProductModelContract
    {
        if (! $data) {
            return null;
        }

        $item = $this->model::find($data->id);

        if (! $item) {
            return null;
        }

        $item->title = $data->title;
        $item->content = $data->content;

        $item->save();

        return $item;
    }
}
