<?php

namespace InetStudio\Products\Repositories;

use InetStudio\AdminPanel\Repositories\BaseRepository;
use InetStudio\Products\Contracts\Models\ProductModelContract;
use InetStudio\Products\Contracts\Repositories\ProductsRepositoryContract;

/**
 * Class ProductsRepository.
 */
class ProductsRepository extends BaseRepository implements ProductsRepositoryContract
{
    /**
     * ProductsRepository constructor.
     *
     * @param ProductModelContract $model
     */
    public function __construct(ProductModelContract $model)
    {
        $this->model = $model;

        $this->defaultColumns = ['id', 'title', 'brand'];
        $this->relations = [
            'media' => function ($query) {
                $query->select(['id', 'model_id', 'model_type', 'collection_name', 'file_name', 'disk']);
            },

            'links' => function ($linksQuery) {
                $linksQuery->select(['id', 'product_id', 'link']);
            },
        ];
    }

    /**
     * Получаем сохраненные объекты пользователя.
     *
     * @param mixed $userID
     * @param array $params
     *
     * @return mixed
     */
    public function getItemsFavoritedByUser($userID, array $params = [])
    {
        $builder = $this->getItemsQuery($params)
            ->whereFavoritedBy('product', $userID);

        $items = $builder->get();

        return $items;
    }
}
