<?php

namespace InetStudio\Products\Services\Front;

use InetStudio\Products\Contracts\Services\Front\ProductsServiceContract;
use InetStudio\Products\Contracts\Repositories\ProductsRepositoryContract;

/**
 * Class ProductsService.
 */
class ProductsService implements ProductsServiceContract
{
    /**
     * @var ProductsRepositoryContract
     */
    private $repository;

    /**
     * EmbeddedProductsDataTableService constructor.
     *
     * @param ProductsRepositoryContract $repository
     */
    public function __construct(ProductsRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Возвращаем модель продукта.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function getProduct(int $id)
    {
        return $this->repository->getItemByID($id);
    }
}
