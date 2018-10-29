<?php

namespace InetStudio\Products\Repositories;

use InetStudio\AdminPanel\Repositories\BaseRepository;
use InetStudio\Products\Contracts\Models\ProductItemModelContract;
use InetStudio\Products\Contracts\Repositories\ProductsItemsRepositoryContract;

/**
 * Class ProductsItemsRepository.
 */
class ProductsItemsRepository extends BaseRepository implements ProductsItemsRepositoryContract
{
    /**
     * ProductsRepository constructor.
     *
     * @param ProductItemModelContract $model
     */
    public function __construct(ProductItemModelContract $model)
    {
        $this->model = $model;

        $this->defaultColumns = ['id', 'title', 'content'];
        $this->relations = [
            'media' => function ($query) {
                $query->select(['id', 'model_id', 'model_type', 'collection_name', 'file_name', 'disk', 'mime_type', 'custom_properties']);
            },
        ];
    }
}
