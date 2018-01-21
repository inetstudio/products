<?php

namespace InetStudio\Products\Http\Controllers\Back;

use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use InetStudio\Products\Models\ProductModel;
use InetStudio\Products\Models\ProductableModel;
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
     * @param string $brand
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function dataBrand(string $brand)
    {
        $items = ProductModel::query();

        return DataTables::of($items)
            ->setTransformer(new ProductTransformer)
            ->rawColumns(['preview', 'material_title', 'actions'])
            ->make();
    }

    /**
     * @param string $brand
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function dataBrandUnlinked(string $brand)
    {
        $items = ProductModel::where('brand', $brand)->get();

        return DataTables::of($items)
            ->setTransformer(new ProductTransformer)
            ->rawColumns(['preview', 'actions'])
            ->make();
    }
}
