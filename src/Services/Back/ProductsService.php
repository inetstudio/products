<?php

namespace InetStudio\Products\Services\Back;

use InetStudio\Products\Contracts\Services\Back\ProductsServiceContract;

/**
 * Class ProductsService.
 */
class ProductsService implements ProductsServiceContract
{
    /**
     * Присваиваем продукты объекту.
     *
     * @param $request
     *
     * @param $item
     */
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