<?php

namespace InetStudio\ProductsPackage\Products\DTO\Back\Resource;

use Spatie\DataTransferObject\DataTransferObject;
use InetStudio\ProductsPackage\Products\Contracts\DTO\Back\Resource\StoreItemDataContract;

class StoreItemData extends DataTransferObject implements StoreItemDataContract
{
    public string $title = '';

    public ?string $content;
}
