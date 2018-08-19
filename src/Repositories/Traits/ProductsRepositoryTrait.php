<?php

namespace InetStudio\Products\Repositories\Traits;

/**
 * Trait ProductsRepositoryTrait.
 */
trait ProductsRepositoryTrait
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
        $builder = $this->getItemsQuery($params)
            ->withProducts($productID);

        return $builder->get();
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
        $builder = $this->getItemsQuery($params)
            ->withAnyProducts($products, 'products.id');

        return $builder->get();
    }
}
