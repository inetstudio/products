<?php

namespace InetStudio\Products\Http\Controllers\Back;

use InetStudio\AdminPanel\Base\Http\Controllers\Controller;
use InetStudio\Products\Contracts\Http\Requests\Back\SaveProductItemRequestContract;
use InetStudio\Products\Contracts\Http\Controllers\Back\ProductsItemsControllerContract;
use InetStudio\Products\Contracts\Http\Responses\Back\ProductsItems\SaveResponseContract;
use InetStudio\Products\Contracts\Http\Responses\Back\ProductsItems\ShowResponseContract;
use InetStudio\Products\Contracts\Http\Responses\Back\ProductsItems\DestroyResponseContract;

/**
 * Class ProductsItemsController.
 */
class ProductsItemsController extends Controller implements ProductsItemsControllerContract
{
    /**
     * Используемые сервисы.
     *
     * @var array
     */
    protected $services;

    /**
     * MessagesController constructor.
     */
    public function __construct()
    {
        $this->services['products_items'] = app()->make('InetStudio\Products\Contracts\Services\Back\ProductsItemsServiceContract');
    }

    /**
     * Получение объекта.
     *
     * @param int $id
     *
     * @return ShowResponseContract
     */
    public function show(int $id = 0): ShowResponseContract
    {
        $item = $this->services['products_items']->getProductItemObject($id);

        return app()->makeWith(ShowResponseContract::class, [
            'item' => $item,
        ]);
    }

    /**
     * Создание объекта.
     *
     * @param SaveProductItemRequestContract $request
     *
     * @return SaveResponseContract
     */
    public function store(SaveProductItemRequestContract $request): SaveResponseContract
    {
        return $this->save($request);
    }

    /**
     * Обновление объекта.
     *
     * @param SaveProductItemRequestContract $request
     * @param int $id
     *
     * @return SaveResponseContract
     */
    public function update(SaveProductItemRequestContract $request, int $id = 0): SaveResponseContract
    {
        return $this->save($request, $id);
    }

    /**
     * Сохранение объекта.
     *
     * @param SaveProductItemRequestContract $request
     * @param int $id
     *
     * @return SaveResponseContract
     */
    private function save(SaveProductItemRequestContract $request, int $id = 0): SaveResponseContract
    {
        $item = $this->services['products_items']->save($request, $id);

        return app()->makeWith(SaveResponseContract::class, [
            'item' => $item,
        ]);
    }

    /**
     * Удаление объекта.
     *
     * @param int $id
     *
     * @return DestroyResponseContract
     */
    public function destroy(int $id = 0): DestroyResponseContract
    {
        $result = $this->services['products_items']->destroy($id);

        return app()->makeWith(DestroyResponseContract::class, [
            'result' => ($result === null) ? false : $result,
        ]);
    }
}
