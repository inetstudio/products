<?php

namespace InetStudio\ProductsPackage\ProductsItems\DTO\Actions\Back\Resource;

use Spatie\DataTransferObject\DataTransferObject;
use InetStudio\ProductsPackage\ProductsItems\Contracts\DTO\Actions\Back\Resource\StoreItemDataContract;

class StoreItemData extends DataTransferObject implements StoreItemDataContract
{
    public string $title = '';

    public ?string $content;
}
