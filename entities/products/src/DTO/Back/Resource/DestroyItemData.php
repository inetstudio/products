<?php

namespace InetStudio\ProductsPackage\Products\DTO\Back\Resource;

use Spatie\DataTransferObject\DataTransferObject;
use InetStudio\ProductsPackage\Products\Contracts\DTO\Back\Resource\DestroyItemDataContract;

class DestroyItemData extends DataTransferObject implements DestroyItemDataContract
{
    public int|string $id;
}
