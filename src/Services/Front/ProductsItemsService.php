<?php

namespace InetStudio\Products\Services\Front;

use InetStudio\Products\Contracts\Services\Front\ProductsItemsServiceContract;

/**
 * Class ProductsItemsService.
 */
class ProductsItemsService implements ProductsItemsServiceContract
{
    /**
     * @var
     */
    public $repository;

    /**
     * ProductsItemsService constructor.
     */
    public function __construct()
    {
        $this->repository = app()->make('InetStudio\Products\Contracts\Repositories\ProductsItemsRepositoryContract');
    }

    /**
     * Возвращаем продукты по id.
     *
     * @param $ids
     * @param array $extColumns
     * @param array $with
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function getProductsItemsByIDs($ids, array $extColumns = [], array $with = [], bool $returnBuilder = false)
    {
        return $this->repository->getItemsByIDs($ids, $extColumns, $with, $returnBuilder);
    }
}
