<?php

namespace InetStudio\Products\Services\Back;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Spatie\Analytics\Period;
use Illuminate\Support\Collection;
use Spatie\Analytics\AnalyticsFacade;
use InetStudio\Products\Contracts\Services\Back\ProductsAnalyticsServiceContract;

/**
 * Class ProductsAnalyticsService.
 */
class ProductsAnalyticsService implements ProductsAnalyticsServiceContract
{
    /**
     * Получаем клики по продуктам из e-com блока.
     *
     * @param Request $request
     *
     * @return Collection
     */
    public function getProductsClicks(Request $request): Collection
    {
        $period = $this->getPeriod($request);

        $rows = $this->analyticsQuery(
            $period,
            'ga:uniqueEvents,ga:users',
            [
                'dimensions' => 'ga:eventCategory,ga:eventAction,ga:eventLabel',
                'filters' => 'ga:eventCategory==Product click',
            ]
        );

        $productsClicks = $this->prepareData($rows);

        return $productsClicks;
    }

    /**
     * Получаем просмотры продуктов из e-com блока.
     *
     * @param Request $request
     *
     * @return Collection
     */
    public function getProductsViews(Request $request): Collection
    {
        $period = $this->getPeriod($request);

        $rows = $this->analyticsQuery(
            $period,
            'ga:uniqueEvents,ga:users',
            [
                'dimensions' => 'ga:eventCategory,ga:eventAction,ga:eventLabel',
                'filters' => 'ga:eventCategory==Product view',
            ]
        );

        $productsViews = $this->prepareData($rows);

        return $productsViews;
    }

    /**
     * Получаем период.
     *
     * @param Request $request
     *
     * @return Period
     */
    protected function getPeriod(Request $request): Period
    {
        $start = config('products.analytics_start_period');
        $start = ($request->filled('startPeriod')) ? $request->get('startPeriod') : $start;
        $start = ($start) ? Carbon::createFromTimestamp(strtotime($start)) : Carbon::now()->subYear();

        $end = ($request->filled('endPeriod')) ? $request->get('endPeriod') : '';
        $end = ($end) ? Carbon::createFromTimestamp(strtotime($end)) : Carbon::now();

        return Period::create($start, $end);
    }

    /**
     * Подготовка полученных данных из GA.
     *
     * @param Collection $rows
     *
     * @return Collection
     */
    protected function prepareData(Collection $rows): Collection
    {
        $data = $rows->map(function ($item) {
            return [
                'brand' => mb_strtoupper(Str::before($item[1], ':')),
                'product' => trim(Str::after($item[1], ':')),
                'shop' => $item[2],
                'count' => $item[3],
                'users' => $item[4],
            ];
        })->groupBy('brand')->map(function ($item) {
            $total = $item->sum('count');
            $users = $item->sum('users');

            return [
                'shops' => $item->groupBy('shop'),
                'total' => $total,
                'users' => $users,
            ];
        });

        return $data;
    }

    /**
     * Запрос в GA на получение данных.
     *
     * @param Period $period
     * @param string $metrics
     * @param array $other
     *
     * @return Collection
     */
    protected function analyticsQuery(Period $period, string $metrics, array $other): Collection
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
            $analyticsData = AnalyticsFacade::performQuery(
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
