<?php

namespace InetStudio\Products\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use InetStudio\Products\Contracts\Http\Requests\Back\SaveProductRequestContract;
use InetStudio\Products\Contracts\Http\Controllers\Back\ProductsControllerContract;
use InetStudio\Products\Contracts\Http\Responses\Back\Products\FormResponseContract;
use InetStudio\Products\Contracts\Http\Responses\Back\Products\SaveResponseContract;
use InetStudio\Products\Contracts\Http\Responses\Back\Products\ShowResponseContract;
use InetStudio\Products\Contracts\Http\Responses\Back\Products\IndexResponseContract;
use InetStudio\Products\Contracts\Http\Responses\Back\Products\DestroyResponseContract;

/**
 * Class ProductsController.
 */
class ProductsController extends Controller implements ProductsControllerContract
{
    /**
     * Используемые сервисы.
     *
     * @var array
     */
    public $services;

    /**
     * ProductsController constructor.
     */
    public function __construct()
    {
        $this->services['products'] = app()->make('InetStudio\Products\Contracts\Services\Back\ProductsServiceContract');
        $this->services['dataTables'] = app()->make('InetStudio\Products\Contracts\Services\Back\ProductsDataTableServiceContract');
    }

    /**
     * Список объектов.
     *
     * @return IndexResponseContract
     */
    public function index(): IndexResponseContract
    {
        $table = $this->services['dataTables']->html();

        return app()->makeWith(IndexResponseContract::class, [
            'data' => compact('table'),
        ]);
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
        $item = $this->services['products']->getProductById($id);

        return app()->makeWith(ShowResponseContract::class, [
            'item' => $item,
        ]);
    }

    /**
     * Редактирование объекта.
     *
     * @param int $id
     *
     * @return FormResponseContract
     */
    public function edit(int $id = 0): FormResponseContract
    {
        $item = $this->services['products']->getItemById($id);

        return app()->makeWith(FormResponseContract::class, [
            'data' => compact('item'),
        ]);
    }

    /**
     * Обновление объекта.
     *
     * @param SaveProductRequestContract $request
     * @param int $id
     *
     * @return SaveResponseContract
     */
    public function update(SaveProductRequestContract $request, int $id = 0): SaveResponseContract
    {
        return $this->save($request, $id);
    }

    /**
     * Сохранение объекта.
     *
     * @param SaveProductRequestContract $request
     * @param int $id
     *
     * @return SaveResponseContract
     */
    private function save(SaveProductRequestContract $request, int $id = 0): SaveResponseContract
    {
        $item = $this->services['products']->save($request, $id);

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
        $result = $this->services['products']->destroy($id);

        return app()->makeWith(DestroyResponseContract::class, [
            'result' => (!! $result),
        ]);
    }
}
