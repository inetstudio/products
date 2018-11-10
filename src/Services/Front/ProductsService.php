<?php

namespace InetStudio\Products\Services\Front;

use Illuminate\Support\Collection;
use InetStudio\AdminPanel\Services\Front\BaseService;
use InetStudio\Favorites\Services\Front\Traits\FavoritesServiceTrait;
use InetStudio\Products\Contracts\Services\Front\ProductsServiceContract;

/**
 * Class ProductsService.
 */
class ProductsService extends BaseService implements ProductsServiceContract
{
    use FavoritesServiceTrait;

    /**
     * ProductsService constructor.
     */
    public function __construct()
    {
        parent::__construct(app()->make('InetStudio\Products\Contracts\Repositories\ProductsRepositoryContract'));
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
