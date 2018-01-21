<?php

namespace InetStudio\Products\Http\Controllers\Back;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use InetStudio\Products\Models\ProductModel;
use InetStudio\Products\Models\ProductableModel;
use InetStudio\AdminPanel\Http\Controllers\Back\Traits\DatatablesTrait;

/**
 * Class ProductsAnalyticsController
 * @package InetStudio\Products\Http\Controllers\Back
 */
class ProductsAnalyticsController extends Controller
{
    use DatatablesTrait;

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
     * @param string $brand
     *
     * @return \Illuminate\Contracts\View\Factory|View
     *
     * @throws \Exception
     */
    public function getBrandAnalytics(string $brand)
    {
        $table = $this->generateTable('products', 'brand')
            ->postAjax(route('back.products.data.analytics.brand', ['brand' => $brand]))
            ->setTableId('products_materials');

        $linkedCount = ProductModel::select(['id'])
            ->where('brand', $brand)
            ->has('productables')
            ->count();

        $tableUnlinked = $this->generateTable('products', 'brand_unlinked')
            ->postAjax(route('back.products.data.analytics.brand.unlinked', ['brand' => $brand]))
            ->setTableId('unlinked_products');

        $unlinkedCount = ProductModel::select(['id'])
            ->where('brand', $brand)
            ->doesntHave('productables')
            ->count();

        return view('admin.module.products::back.pages.analytics.brand', compact('table', 'linkedCount', 'tableUnlinked', 'unlinkedCount'));
    }
}
