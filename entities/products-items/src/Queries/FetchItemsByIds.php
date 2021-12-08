<?php

namespace InetStudio\ProductsPackage\ProductsItems\Queries;

use Illuminate\Database\Eloquent\Collection;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Queries\FetchItemsByIdsContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Models\ProductItemModelContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\DTO\Queries\FetchItemsByIdsDataContract;

class FetchItemsByIds implements FetchItemsByIdsContract
{
    public function __construct(
        protected ProductItemModelContract $model
    ) {}

    public function execute(FetchItemsByIdsDataContract $data): Collection
    {
        return $this->model::find($data->ids);
    }
}
