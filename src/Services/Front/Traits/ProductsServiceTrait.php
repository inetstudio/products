<?php

namespace InetStudio\Products\Services\Front\Traits;

/**
 * Trait ProductsServiceTrait.
 */
trait ProductsServiceTrait
{
    /**
     * Получаем объекты по продуктам.
     *
     * @param int $productID
     * @param array $params
     *
     * @return mixed
     */
    public function getItemsByProduct(int $productID, array $params = [])
    {
        return $this->model
            ->buildQuery($params)
            ->withProducts($productID);
    }

    /**
     * Получаем объекты с любыми продуктами.
     *
     * @param $products
     * @param array $params
     *
     * @return mixed
     */
    public function getItemsByAnyProduct($products, array $params = [])
    {
        return $this->model
            ->buildQuery($params)
            ->withAnyProducts($products, 'products.id');
    }
}
