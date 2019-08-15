<?php

namespace InetStudio\Products\Services\Back;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use InetStudio\AdminPanel\Base\Services\BaseService;
use InetStudio\Products\Contracts\Models\ProductModelContract;
use InetStudio\Products\Contracts\Services\Back\ItemsServiceContract;

/**
 * Class ItemsService.
 */
class ItemsService extends BaseService implements ItemsServiceContract
{
    /**
     * ItemsService constructor.
     *
     * @param  ProductModelContract  $model
     */
    public function __construct(ProductModelContract $model)
    {
        parent::__construct($model);
    }

    /**
     * Сохраняем модель.
     *
     * @param array $data
     * @param int $id
     *
     * @return ProductModelContract
     */
    public function save(array $data, int $id): ProductModelContract
    {
        $action = ($id) ? 'отредактирован' : 'создан';

        $itemData = Arr::only($data, $this->model->getFillable());
        $item = $this->saveModel($itemData, $id);

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
