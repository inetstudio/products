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
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function getProductsItemsByIDs($ids, bool $returnBuilder = false)
    {
        return $this->repository->getItemsByIDs($ids, $returnBuilder);
    }
}
