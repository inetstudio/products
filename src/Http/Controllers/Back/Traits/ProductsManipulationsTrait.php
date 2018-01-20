<?php

namespace InetStudio\Products\Http\Controllers\Back\Traits;

/**
 * Trait ProductsManipulationsTrait
 * @package InetStudio\Products\Http\Controllers\Back\Traits
 */
trait ProductsManipulationsTrait
{
    /**
     * Сохраняем продукты.
     *
     * @param $item
     * @param $request
     */
    private function saveProducts($item, $request): void
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
