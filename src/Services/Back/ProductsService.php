<?php

namespace InetStudio\Products\Services\Back;

use Illuminate\Support\Facades\Session;
use InetStudio\AdminPanel\Services\Back\BaseService;
use InetStudio\Products\Contracts\Models\ProductModelContract;
use InetStudio\Products\Contracts\Services\Back\ProductsServiceContract;
use InetStudio\Products\Contracts\Http\Requests\Back\SaveProductRequestContract;

/**
 * Class ProductsService.
 */
class ProductsService extends BaseService implements ProductsServiceContract
{
    /**
     * ProductsService constructor.
     */
    public function __construct()
    {
        parent::__construct(app()->make('InetStudio\Products\Contracts\Repositories\ProductsRepositoryContract'));
    }

    /**
     * Сохраняем модель.
     *
     * @param SaveProductRequestContract $request
     * @param int $id
     *
     * @return ProductModelContract
     */
    public function save(SaveProductRequestContract $request, int $id): ProductModelContract
    {
        $action = ($id) ? 'отредактирован' : 'создан';
        $item = $this->repository->save($request->only($this->repository->getModel()->getFillable()), $id);

        event(app()->makeWith('InetStudio\Products\Contracts\Events\Back\ModifyProductEventContract', [
            'object' => $item,
        ]));

        Session::flash('success', 'Продукт «'.$item->title.'» успешно '.$action);

        return $item;
    }

    /**
     * Присваиваем продукты объекту.
     *
     * @param $request
     *
     * @param $item
     */
    public function attachToObject($request, $item)
    {
        if ($request->filled('products')) {
            $ids = [];

            foreach ($request->get('products') as $product) {
                $ids[] = $product['id'];
            }

            $item->syncProducts($ids)->get();
        } else {
            $item->detachProducts($item->products);
        }
    }
}
