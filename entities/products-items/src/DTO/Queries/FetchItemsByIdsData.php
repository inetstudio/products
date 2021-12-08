<?php

namespace InetStudio\ProductsPackage\ProductsItems\DTO\Queries;

use Spatie\DataTransferObject\DataTransferObject;
use InetStudio\ProductsPackage\ProductsItems\Contracts\DTO\Queries\FetchItemsByIdsDataContract;

class FetchItemsByIdsData extends DataTransferObject implements FetchItemsByIdsDataContract
{
    public array $ids;
}
