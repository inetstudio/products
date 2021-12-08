<?php

namespace InetStudio\ProductsPackage\Products\DTO\Back\Resource;

use Spatie\DataTransferObject\DataTransferObject;
use InetStudio\ProductsPackage\Products\Contracts\DTO\Back\Resource\ShowItemDataContract;

class ShowItemData extends DataTransferObject implements ShowItemDataContract
{
    public int|string $id;
}
