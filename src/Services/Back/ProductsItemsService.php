<?php

namespace InetStudio\Products\Services\Back;

use InetStudio\Products\Contracts\Models\ProductItemModelContract;
use InetStudio\Products\Contracts\Services\Back\ProductsItemsServiceContract;
use InetStudio\Products\Contracts\Http\Requests\Back\SaveProductItemRequestContract;

/**
 * Class ProductsItemsService.
 */
class ProductsItemsService implements ProductsItemsServiceContract
{
    /**
     * Используемые сервисы.
     *
     * @var array
     */
    public $services = [];

    /**
     * @var
     */
    public $repository;

    /**
     * ProductsItemsService constructor.
     */
    public function __construct()
    {
        $this->services['images'] = app()->make('InetStudio\Uploads\Contracts\Services\Back\ImagesServiceContract');

        $this->repository = app()->make('InetStudio\Products\Contracts\Repositories\ProductsItemsRepositoryContract');
    }

    /**
     * Получаем объект модели.
     *
     * @param int $id
     *
     * @return ProductItemModelContract
     */
    public function getProductItemObject(int $id = 0)
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
    public function getProductsItemsByIDs($ids, array $params = [])
    {
        return $this->repository->getItemsByIDs($ids, $params);
    }

    /**
     * Сохраняем модель.
     *
     * @param SaveProductItemRequestContract $request
     * @param int $id
     *
     * @return ProductItemModelContract
     */
    public function save(SaveProductItemRequestContract $request, int $id): ProductItemModelContract
    {
        $item = $this->repository->save($request->only($this->repository->getModel()->getFillable()), $id);

        $images = (config('products.images.conversions.product_item')) ? array_keys(config('products.images.conversions.product_item')) : [];
        $this->services['images']->attachToObject($request, $item, $images, 'products', 'product_item');

        event(app()->makeWith('InetStudio\Products\Contracts\Events\Back\ModifyProductItemEventContract', [
            'object' => $item,
        ]));

        return $item;
    }

    /**
     * Удаляем модель.
     *
     * @param int $id
     *
     * @return bool|null
     */
    public function destroy(int $id): ?bool
    {
        return $this->repository->destroy($id);
    }
}
