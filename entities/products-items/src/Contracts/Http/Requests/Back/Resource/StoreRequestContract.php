<?php

namespace InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Requests\Back\Resource;

use InetStudio\ProductsPackage\ProductsItems\Contracts\DTO\Actions\Back\Resource\StoreItemDataContract;

interface StoreRequestContract
{
    public function getDataObject(): StoreItemDataContract;
}
