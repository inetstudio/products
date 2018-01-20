<?php

namespace InetStudio\Products\Http\Controllers\Back;

use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use InetStudio\Products\Models\ProductModel;
use InetStudio\Products\Transformers\Back\ProductTransformer;
use InetStudio\Products\Transformers\Back\ProductEmbeddedTransformer;

/**
 * Class ProductsDataController
 * @package InetStudio\Products\Http\Controllers\Back
 */
class ProductsDataController extends Controller
{
    /**
     * DataTables ServerSide.
     *
     * @param $type
     *
     * @return mixed
     *
     * @throws \Exception
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
     * @return mixed
     *
     * @throws \Exception
     */
    public function dataBrand()
    {
        $items = ProductModel::query();

        return DataTables::of($items)
            ->setTransformer(new ProductTransformer)
            ->rawColumns(['preview', 'actions'])
            ->make();
    }

    /**
     * @return mixed
     *
     * @throws \Exception
     */
    public function dataBrandUnlinked()
    {
        $items = ProductModel::query();

        return DataTables::of($items)
            ->setTransformer(new ProductTransformer)
            ->rawColumns(['preview', 'actions'])
            ->make();
    }
}
