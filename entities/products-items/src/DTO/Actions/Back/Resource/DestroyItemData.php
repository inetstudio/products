<?php

namespace InetStudio\ProductsPackage\ProductsItems\DTO\Actions\Back\Resource;

use Spatie\DataTransferObject\DataTransferObject;
use InetStudio\ProductsPackage\ProductsItems\Contracts\DTO\Actions\Back\Resource\DestroyItemDataContract;

class DestroyItemData extends DataTransferObject implements DestroyItemDataContract
{
    public int|string $id;
}
