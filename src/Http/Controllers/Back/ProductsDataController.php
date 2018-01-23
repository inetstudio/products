<?php

namespace InetStudio\Products\Http\Controllers\Back;

use Carbon\Carbon;
use Spatie\Analytics\Period;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use InetStudio\Products\Models\ProductModel;
use InetStudio\Products\Models\ProductableModel;
use InetStudio\Products\Transformers\Back\BrandTransformer;
use InetStudio\Products\Transformers\Back\ProductTransformer;
use InetStudio\Products\Transformers\Back\ProductableTransformer;
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
    public function dataBrands()
    {
        $productables = ProductableModel::with(['product' => function ($productQuery) {
            $productQuery->select(['id', 'brand']);
        }])->select(['product_model_id'])->get();

        $items = $productables->groupBy('product.brand')->map(function ($item, $key) {
            return [
                'brand' => $key,
                'references' => $item->count(),
            ];
        });

        $productsClicks = $this->getProductsClicks();
        $productsViews = $this->getProductsViews();

        $views = [];
        $clicks = [];

        foreach ($productsViews as $eventView) {
            $brand = strtok($eventView[1], ':');
            $shop = $eventView[2];

            if (! isset($views[$brand][$shop])) {
                $views[$brand][$shop] = 0;
            }

            $views[$brand][$shop] += $eventView[3];
        }

        foreach ($productsClicks as $eventClick) {
            $brand = strtok($eventClick[1], ':');
            $shop = $eventClick[2];

            if (! isset($clicks[$brand][$shop])) {
                $clicks[$brand][$shop] = 0;
            }

            $clicks[$brand][$shop] += $eventClick[3];
        }

        foreach ($views as $brand => $data) {
            if (isset($items[$brand])) {
                $items[$brand]['views'] = $data;
            }
        }

        foreach ($clicks as $brand => $data) {
            if (isset($items[$brand])) {
                $items[$brand]['clicks'] = $data;
            }
        }

        return DataTables::of($items)
            ->setTransformer(new BrandTransformer($items->sum('references')))
            ->rawColumns(['brand'])
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

    private function getProductsClicks()
    {
        $start = config('products.analytics_start_period');

        $period = ($start) ? Period::create(Carbon::createFromTimestamp(strtotime($start)), Carbon::today()) : Period::years(1);

        $rows = $this->analyticsQuery(
            $period,
            'ga:uniqueEvents',
            [
                'dimensions' => 'ga:eventCategory,ga:eventAction,ga:eventLabel',
                'filters' => 'ga:eventCategory==Product click',
            ]
        );

        return $rows;
    }

    private function getProductsView()
    {
        $start = config('products.analytics_start_period');

        $period = ($start) ? Period::create(Carbon::createFromTimestamp(strtotime($start)), Carbon::today()) : Period::years(1);

        $rows = $this->analyticsQuery(
            $period,
            'ga:uniqueEvents',
            [
                'dimensions' => 'ga:eventCategory,ga:eventAction,ga:eventLabel',
                'filters' => 'ga:eventCategory==Product view',
            ]
        );

        return $rows;
    }

    private function analyticsQuery($period, $metrics, $other)
    {
        $rows = [];

        $stop = false;
        $offset = 1;
        $limit = 1000;

        $requestData = array_merge([
            'start-index' => $offset,
            'max-results' => $limit,
        ], $other);

        while (! $stop) {
            $analyticsData = \Analytics::performQuery(
                $period,
                $metrics,
                $requestData
            );

            $offset += $limit;

            $requestData['start-index'] = $offset;

            if (! $analyticsData->rows) {
                $stop = true;
            } else {
                $rows = array_merge($rows, $analyticsData->rows);
            }
        }

        return collect($rows);
    }
}
