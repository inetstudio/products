<?php

namespace InetStudio\Products\Traits;

trait ProductsManipulationsTrait
{
    /**
     * Сохраняем продукты.
     *
     * @param $item
     * @param $request
     */
    private function saveProducts($item, $request)
    {
        if ($request->has('products')) {
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
