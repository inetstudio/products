<?php

namespace InetStudio\ProductsPackage\Products\Services\Front;

use Illuminate\Support\Collection;
use InetStudio\ProductsPackage\Products\Services\ItemsService as BaseItemsService;
use InetStudio\ProductsPackage\Products\Contracts\Services\Front\ItemsServiceContract;

class ItemsService extends BaseItemsService implements ItemsServiceContract
{
    public function getItemsByMaterials(Collection $materials): Collection
    {
        return $materials->map(function ($item) {
            return (isset($item['products'])) ? $item['products'] : [];
        })->filter()->collapse()->unique('id');
    }
}
