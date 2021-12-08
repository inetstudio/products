<?php

namespace InetStudio\ProductsPackage\ProductsItems\Contracts\Queries;

use Illuminate\Database\Eloquent\Collection;
use InetStudio\ProductsPackage\ProductsItems\Contracts\DTO\Queries\FetchItemsByIdsDataContract;

interface FetchItemsByIdsContract
{
    public function execute(FetchItemsByIdsDataContract $data): Collection;
}
