<?php

namespace InetStudio\ProductsPackage\Links\DTO\Back\Resource;

use Spatie\DataTransferObject\DataTransferObject;
use InetStudio\ProductsPackage\Links\Contracts\DTO\Back\Resource\UpdateItemDataContract;

class UpdateItemData extends DataTransferObject implements UpdateItemDataContract
{
    public int|string $id;

    public int|string $productId;

    public string $href;
}
