<?php

namespace InetStudio\Products\Http\Controllers\Back;

use Illuminate\View\View;
use InetStudio\AdminPanel\Base\Http\Controllers\Controller;
use InetStudio\Products\Models\ProductModel;
use InetStudio\AdminPanel\Http\Controllers\Back\Traits\DatatablesTrait;

/**
 * Class ProductsAnalyticsController.
 */
class ProductsAnalyticsController extends Controller
{
    use DatatablesTrait;

    /**
     * Отображаем страницу аналитики.
     *
     * @return View
     *
     * @throws \Exception
     */
    public function getBrandsAnalytics(): View
    {
        $table = $this->generateTable('products', 'brands');

        return view('admin.module.products::back.pages.analytics.index', compact('table'));
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
