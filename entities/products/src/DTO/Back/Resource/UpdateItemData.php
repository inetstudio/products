<?php

namespace InetStudio\ProductsPackage\Products\DTO\Back\Resource;

use Spatie\DataTransferObject\DataTransferObject;
use InetStudio\ProductsPackage\Products\Contracts\DTO\Back\Resource\UpdateItemDataContract;

class UpdateItemData extends DataTransferObject implements UpdateItemDataContract
{
    public int|string $id;

    public string $title = '';

    public ?string $content;
}
