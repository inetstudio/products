<?php

namespace InetStudio\ProductsPackage\Links\DTO\Back\Resource;

use Spatie\DataTransferObject\DataTransferObject;
use InetStudio\ProductsPackage\Links\Contracts\DTO\Back\Resource\StoreItemDataContract;

class StoreItemData extends DataTransferObject implements StoreItemDataContract
{
    public int|string $productId;

    public string $href;
}
