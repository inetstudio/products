<?php

namespace InetStudio\ProductsPackage\ProductsItems\DTO\Actions\Back\Resource;

use Spatie\DataTransferObject\DataTransferObject;
use InetStudio\ProductsPackage\ProductsItems\Contracts\DTO\Actions\Back\Resource\UpdateItemDataContract;

class UpdateItemData extends DataTransferObject implements UpdateItemDataContract
{
    public int|string $id;

    public string $title = '';

    public ?string $content;
}
