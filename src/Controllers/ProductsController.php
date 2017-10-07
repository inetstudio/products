<?php

namespace InetStudio\Products\Controllers;

use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use InetStudio\Products\Models\ProductModel;
use InetStudio\AdminPanel\Traits\DatatablesTrait;
use InetStudio\Products\Transformers\ProductTransformer;
use InetStudio\Products\Transformers\ProductEmbeddedTransformer;

/**
 * Контроллер для управления статьями.
 *
 * Class ContestByTagStatusesController
 */
class ProductsController extends Controller
{
    use DatatablesTrait;

    /**
     * Список продуктов.
     *
     * @param DataTables $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(DataTables $dataTable)
    {
        $table = $this->generateTable($dataTable, 'products', 'index');

        return view('admin.module.products::pages.index', compact('table'));
    }

    /**
     * Datatables serverside.
     *
     * @param $type
     * @return mixed
     */
    public function data($type = '')
    {
        $items = ProductModel::query();

        $transformer = (! $type) ? new ProductTransformer : new ProductEmbeddedTransformer;

        return DataTables::of($items)
            ->setTransformer($transformer)
            ->rawColumns(['preview', 'actions'])
            ->make();
    }

    /**
     * Редактирование статьи.
     *
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id = null)
    {
        if (! is_null($id) && $id > 0 && $item = ProductModel::find($id)) {
            
            return view('admin.module.products::pages.form', [
                'item' => $item,
            ]);
        } else {
            abort(404);
        }
    }

    /**
     * Удаление статьи.
     *
     * @param null $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id = null)
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
}
