<?php

namespace InetStudio\ProductsPackage\ProductsItems\Actions\Back\Resource;

use InetStudio\ProductsPackage\ProductsItems\Contracts\Models\ProductItemModelContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Actions\Back\Resource\UpdateActionContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\DTO\Actions\Back\Resource\UpdateItemDataContract;

class UpdateAction implements UpdateActionContract
{
    public function __construct(
        protected ProductItemModelContract $model
    ) {}

    public function execute(UpdateItemDataContract $data): ?ProductItemModelContract
    {
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
