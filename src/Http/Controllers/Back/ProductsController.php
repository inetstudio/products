<?php

namespace InetStudio\Products\Http\Controllers\Back;

use Illuminate\View\View;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use InetStudio\Products\Models\ProductModel;
use InetStudio\Products\Models\ProductableModel;
use InetStudio\AdminPanel\Http\Controllers\Back\Traits\DatatablesTrait;

/**
 * Class ProductsController
 * @package InetStudio\Products\Http\Controllers\Back
 */
class ProductsController extends Controller
{
    use DatatablesTrait;

    /**
     * Список продуктов.
     *
     * @param DataTables $dataTable
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * @throws \Exception
     */
    public function index(DataTables $dataTable): View
    {
        $table = $this->generateTable($dataTable, 'products', 'index');

        return view('admin.module.products::back.pages.index', compact('table'));
    }

    /**
     * Редактирование продукта.
     *
     * @param null $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id = null): View
    {
        if (! is_null($id) && $id > 0 && $item = ProductModel::find($id)) {
            return view('admin.module.products::back.pages.form', [
                'item' => $item,
            ]);
        } else {
            abort(404);
        }
    }

    /**
     * Удаление продукта.
     *
     * @param null $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id = null): JsonResponse
    {
        if (! is_null($id) && $id > 0 && $item = ProductModel::find($id)) {
            $item->delete();

            return response()->json([
                'success' => true,
            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }

    /**
     * Отображаем страницу аналитики.
     *
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function getAnalytics(): View
    {
        $productables = ProductableModel::with(['product' => function ($productQuery) {
            $productQuery->select(['id', 'brand']);
        }])->select(['product_model_id'])->get();

        $data = $productables->groupBy('product.brand')->mapWithKeys(function ($item, $key) {
            return [$key => $item->count()];
        });

        return view('admin.module.products::back.pages.analytics.index', [
            'brands' => $data
        ]);
    }

    /**
     * Отображаем страницу продуктов бренда.
     *
     * @param DataTables $dataTable
     *
     * @return \Illuminate\Contracts\View\Factory|View
     *
     * @throws \Exception
     */
    public function getBrandAnalytics(DataTables $dataTable)
    {
        $table = $this->generateTable($dataTable, 'products', 'brand');
        $tableUnlinked = $this->generateTable($dataTable, 'products', 'brand_unlinked');

        return view('admin.module.products::back.pages.analytics.brand', compact('table', 'tableUnlinked'));
    }
}
