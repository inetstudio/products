<?php

namespace InetStudio\Products\Http\Controllers\Back;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use InetStudio\Products\Models\ProductModel;
use InetStudio\Products\Models\ProductableModel;
use InetStudio\Products\Transformers\Back\BrandTransformer;
use InetStudio\Products\Transformers\Back\ProductTransformer;
use InetStudio\Products\Transformers\Back\ProductableTransformer;
use InetStudio\Products\Transformers\Back\ProductEmbeddedTransformer;
use InetStudio\Products\Contracts\Services\ProductsAnalyticsServiceContract;
use InetStudio\Products\Contracts\Http\Controllers\Back\ProductsDataControllerContract;
use InetStudio\Products\Contracts\Services\Back\EmbeddedProductsDataTableServiceContract;

/**
 * Class ProductsDataController.
 */
class ProductsDataController extends Controller implements ProductsDataControllerContract
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
     * Получаем данные для отображения во встроенной таблице.
     *
     * @param EmbeddedProductsDataTableServiceContract $dataTableService
     *
     * @return mixed
     */
    public function dataEmbedded(EmbeddedProductsDataTableServiceContract $dataTableService)
    {
        return $dataTableService->ajax();
    }

    /**
     * Данные для аналитики по брендам.
     *
     * @param Request $request
     * @param ProductsAnalyticsServiceContract $analytics
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function dataBrands(Request $request, ProductsAnalyticsServiceContract $analytics)
    {
        $productables = ProductableModel::with(['product' => function ($productQuery) {
            $productQuery->select(['id', 'brand']);
        }])->has('product')->select(['product_model_id'])->get();

        $items = $productables->groupBy('product.brand')->mapWithKeys(function ($item, $key) {
            return [mb_strtoupper($key) => [
                'brand' => $key,
                'references' => $item->count(),
            ]];
        });

        $productsClicks = $analytics->getProductsClicks($request);
        $productsViews = $analytics->getProductsViews($request);

        $items = $items->mapWithKeys(function ($item, $key) use ($productsViews, $productsClicks) {
            if ($productsViews->has($key)) {
                $item['views'] = $productsViews->get($key);
            }

            if ($productsClicks->has($key)) {
                $item['clicks'] = $productsClicks->get($key);
            }

            return [$key => $item];
        });

        return DataTables::of($items)
            ->setTransformer(new BrandTransformer($items->sum('references')))
            ->rawColumns(['brand', 'shops'])
            ->make();
    }

    /**
     * Данные по привязкам продуктов бренда к материалам.
     *
     * @param string $brand
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function dataBrand(string $brand)
    {
        $items = ProductableModel::with(['product' => function ($productQuery) {
            $productQuery->with(['media' => function ($query) {
                $query->select(['id', 'model_id', 'model_type', 'collection_name', 'file_name', 'disk']);
            }])
                ->select(['id', 'brand', 'title']);
        }, 'productable'])
            ->whereHas('product', function ($productQuery) use ($brand) {
                $productQuery->where('brand', $brand);
            })->get()->filter(function ($value) {
                return $value->productable;
            });

        return DataTables::of($items)
            ->setTransformer(new ProductableTransformer)
            ->rawColumns(['preview', 'material_title', 'actions'])
            ->make();
    }

    /**
     * Данные по непривязанным продуктам бренда к материалам.
     *
     * @param string $brand
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function dataBrandUnlinked(string $brand)
    {
        $items = ProductModel::with(['media' => function ($query) {
            $query->select(['id', 'model_id', 'model_type', 'collection_name', 'file_name', 'disk']);
        }])
            ->select(['id', 'brand', 'title', 'created_at', 'updated_at'])
            ->where('brand', $brand)
            ->doesntHave('productables')
            ->get();

        return DataTables::of($items)
            ->setTransformer(new ProductTransformer)
            ->rawColumns(['preview', 'actions'])
            ->make();
    }
}
