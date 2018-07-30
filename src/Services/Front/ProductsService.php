<?php

namespace InetStudio\Products\Services\Front;

use Illuminate\Http\Request;
use InetStudio\Products\Contracts\Models\ProductModelContract;
use InetStudio\Products\Contracts\Services\Front\ProductsServiceContract;

/**
 * Class ProductsService.
 */
class ProductsService implements ProductsServiceContract
{
    /**
     * @var
     */
    private $repository;

    /**
     * EmbeddedProductsDataTableService constructor.
     */
    public function __construct()
    {
        $this->repository = app()->make('InetStudio\Products\Contracts\Repositories\ProductsRepositoryContract');
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

    /**
     * Получаем сохраненные объекты пользователя.
     *
     * @param mixed $userID
     * @param array $extColumns
     * @param array $with
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function getProductsFavoritedByUser($userID, array $extColumns = [], array $with = [], bool $returnBuilder = false)
    {
        return $this->repository->getItemsFavoritedByUser($userID, $extColumns, $with, $returnBuilder);
    }

    /**
     * Возвращаем данные для виджета.
     *
     * @param Request $request
     * @param ProductModelContract $item
     *
     * @return array
     */
    public function getWidgetData(Request $request,
                                  ProductModelContract $item): array
    {
        $data = [
            'product' => $item,
        ];

        return $data;
    }
}
