<?php

namespace InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Requests\Back\Resource;

use InetStudio\ProductsPackage\ProductsItems\Contracts\DTO\Queries\FetchItemsByIdsDataContract;

interface ShowRequestContract
{
    public function getDataObject(): FetchItemsByIdsDataContract;
}
