<?php

namespace InetStudio\Products\Repositories;

use Illuminate\Database\Eloquent\Builder;
use InetStudio\Products\Contracts\Models\ProductItemModelContract;
use InetStudio\Products\Contracts\Repositories\ProductsItemsRepositoryContract;

/**
 * Class ProductsItemsRepository.
 */
class ProductsItemsRepository implements ProductsItemsRepositoryContract
{
    /**
     * @var ProductItemModelContract
     */
    public $model;

    /**
     * ProductsItemsRepository constructor.
     *
     * @param ProductItemModelContract $model
     */
    public function __construct(ProductItemModelContract $model)
    {
        $this->model = $model;
    }

    /**
     * Получаем модель репозитория.
     *
     * @return ProductItemModelContract
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Возвращаем объект по id, либо создаем новый.
     *
     * @param int $id
     *
     * @return ProductItemModelContract
     */
    public function getItemByID(int $id): ProductItemModelContract
    {
        return $this->model::find($id) ?? new $this->model;
    }

    /**
     * Возвращаем объекты по списку id.
     *
     * @param $ids
     * @param array $extColumns
     * @param array $with
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function getItemsByIDs($ids, array $extColumns = [], array $with = [], bool $returnBuilder = false)
    {
        $builder = $this->getItemsQuery($extColumns, $with)->whereIn('id', (array) $ids);

        if ($returnBuilder) {
            return $builder;
        }

        return $builder->get();
    }

    /**
     * Сохраняем объект.
     *
     * @param array $data
     * @param int $id
     *
     * @return ProductItemModelContract
     */
    public function save(array $data, int $id): ProductItemModelContract
    {
        $item = $this->getItemByID($id);
        $item->fill($data);
        $item->save();

        return $item;
    }

    /**
     * Удаляем объект.
     *
     * @param int $id
     *
     * @return bool
     */
    public function destroy($id): ?bool
    {
        return $this->getItemByID($id)->delete();
    }

    /**
     * Ищем объекты.
     *
     * @param array $conditions
     * @param array $extColumns
     * @param array $with
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function searchItems(array $conditions, array $extColumns = [], array $with = [], bool $returnBuilder = false)
    {
        $builder = $this->getItemsQuery($extColumns, $with)->where($conditions);

        if ($returnBuilder) {
            return $builder;
        }

        return $builder->get();
    }

    /**
     * Получаем все объекты.
     *
     * @param array $extColumns
     * @param array $with
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function getAllItems(array $extColumns = [], array $with = [], bool $returnBuilder = false)
    {
        $builder = $this->getItemsQuery(array_merge($extColumns, ['created_at', 'updated_at']), $with);

        if ($returnBuilder) {
            return $builder;
        }

        return $builder->get();
    }

    /**
     * Возвращаем запрос на получение объектов.
     *
     * @param array $extColumns
     * @param array $with
     *
     * @return Builder
     */
    protected function getItemsQuery($extColumns = [], $with = []): Builder
    {
        $defaultColumns = ['id', 'title', 'content'];

        $relations = [];

        return $this->model::select(array_merge($defaultColumns, $extColumns))
            ->with(array_intersect_key($relations, array_flip($with)));
    }
}
