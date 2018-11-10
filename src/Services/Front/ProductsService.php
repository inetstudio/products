<?php

namespace InetStudio\Products\Services\Front;

use Illuminate\Http\Request;
use InetStudio\AdminPanel\Services\Front\BaseService;
use InetStudio\Products\Contracts\Models\ProductModelContract;
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
