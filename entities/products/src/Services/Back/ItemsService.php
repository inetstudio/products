<?php

namespace InetStudio\ProductsPackage\Products\Services\Back;

use InetStudio\ProductsPackage\Products\Services\ItemsService as BaseItemsService;
use InetStudio\ProductsPackage\Products\Contracts\Services\Back\ItemsServiceContract;

class ItemsService extends BaseItemsService implements ItemsServiceContract
{
    public function attachToObject($request, $item)
    {
        if ($request->filled('products')) {
            $ids = [];

            foreach ($request->get('products') as $product) {
                $ids[] = $product['id'];
            }

            $item->syncProducts($ids)->get();
        } else {
            $item->detachProducts($item->products);
        }
    }
}
