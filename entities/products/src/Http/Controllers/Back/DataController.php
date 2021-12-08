<?php

namespace InetStudio\ProductsPackage\Products\Http\Controllers\Back;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use InetStudio\AdminPanel\Base\Http\Controllers\Controller;
use InetStudio\Products\Transformers\Back\BrandTransformer;
use InetStudio\Products\Transformers\Back\ProductableTransformer;
use InetStudio\ProductsPackage\Products\Contracts\Http\Controllers\Back\DataControllerContract;
use InetStudio\ProductsPackage\Products\Contracts\Http\Requests\Back\Data\GetIndexDataRequestContract;
use InetStudio\ProductsPackage\Products\Contracts\Http\Requests\Back\Data\GetModalDataRequestContract;
use InetStudio\ProductsPackage\Products\Contracts\Http\Responses\Back\Data\GetIndexDataResponseContract;
use InetStudio\ProductsPackage\Products\Contracts\Http\Responses\Back\Data\GetModalDataResponseContract;
use InetStudio\ProductsPackage\Products\Contracts\Http\Requests\Back\Data\GetEmbeddedDataRequestContract;
use InetStudio\ProductsPackage\Products\Contracts\Http\Responses\Back\Data\GetEmbeddedDataResponseContract;

class DataController extends Controller implements DataControllerContract
{
    public function getEmbeddedData(GetEmbeddedDataRequestContract $request, GetEmbeddedDataResponseContract $response): GetEmbeddedDataResponseContract
    {
        return $response;
    }

    public function getIndexData(GetIndexDataRequestContract $request, GetIndexDataResponseContract $response): GetIndexDataResponseContract
    {
        return $response;
    }

    public function getModalData(GetModalDataRequestContract $request, GetModalDataResponseContract $response): GetModalDataResponseContract
    {
        return $response;
    }

    public function dataBrands(Request $request, ProductsAnalyticsServiceContract $analytics)
    {
        $brandsReferences = $analytics->getBrandsReferences();
        $productsClicks = $analytics->getProductsClicks($request);
        $productsViews = $analytics->getProductsViews($request);

        $items = $brandsReferences->mapWithKeys(function ($item, $key) use ($productsViews, $productsClicks) {
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
                $query->select(['id', 'model_id', 'model_type', 'collection_name', 'file_name', 'disk', 'conversions_disk', 'uuid']);
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
            $query->select(['id', 'model_id', 'model_type', 'collection_name', 'file_name', 'disk', 'conversions_disk', 'uuid']);
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
