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
     * @param array $extColumns
     * @param array $with
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function getItemsByProduct(int $productID, array $extColumns = [], array $with = [], bool $returnBuilder = false)
    {
        $builder = $this->getItemsQuery($extColumns, $with)->withProducts($productID);

        if ($returnBuilder) {
            return $builder;
        }

        return $builder->get();
    }

    /**
     * Получаем объекты с любыми продуктами.
     *
     * @param $products
     * @param array $extColumns
     * @param array $with
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function getItemsByAnyProduct($products, array $extColumns = [], array $with = [], bool $returnBuilder = false)
    {
        $builder = $this->getItemsQuery($extColumns, $with)->withAnyProducts($products, 'products.id');

        if ($returnBuilder) {
            return $builder;
        }

        return $builder->get();
    }
}
