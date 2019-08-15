<?php

namespace InetStudio\Products\Services\Front;

use Illuminate\Support\Collection;
use InetStudio\AdminPanel\Base\Services\BaseService;
use InetStudio\Products\Contracts\Models\ProductModelContract;
use InetStudio\Favorites\Services\Front\Traits\FavoritesServiceTrait;
use InetStudio\Products\Contracts\Services\Front\ItemsServiceContract;

/**
 * Class ItemsService.
 */
class ItemsService extends BaseService implements ItemsServiceContract
{
    use FavoritesServiceTrait;

    /**
     * @var string
     */
    protected $favoritesType = 'product';

    /**
     * ProductsService constructor.
     *
     * @param  ProductModelContract  $model
     */
    public function __construct(ProductModelContract $model)
    {
        parent::__construct($model);
    }

    /**
     * Возвращаем объекты, привязанные к материалам.
     *
     * @param Collection $materials
     *
     * @return Collection
     */
    public function getItemsByMaterials(Collection $materials): Collection
    {
        return $materials->map(function ($item) {
            return (isset($item['products'])) ? $item['products'] : [];
        })->filter()->collapse()->unique('id');
    }
}
