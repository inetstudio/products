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
    public $repository;

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
     * Получаем объекты по списку id.
     *
     * @param array|int $ids
     * @param array $params
     *
     * @return mixed
     */
    public function getProductsByIDs($ids, array $params = [])
    {
        return $this->repository->getItemsByIDs($ids, $params);
    }

    /**
     * Получаем сохраненные объекты пользователя.
     *
     * @param mixed $userID
     * @param array $params
     *
     * @return mixed
     */
    public function getProductsFavoritedByUser($userID, array $params = [])
    {
        return $this->repository->getItemsFavoritedByUser($userID, $params);
    }

    /**
     * Получаем все объекты.
     *
     * @param array $params
     *
     * @return mixed
     */
    public function getAllProducts(array $params = [])
    {
        return $this->repository->getAllItems($params);
    }

    /**
     * Возвращаем данные для виджета.
     *
     * @param Request $request
     * @param ProductModelContract $item
     *
     * @return array
     */
    public function getWidgetData(Request $request, ProductModelContract $item): array
    {
        $data = [
            'product' => $item,
        ];

        return $data;
    }
}
