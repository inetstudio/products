<?php

namespace InetStudio\Products\Transformers\Back;

use League\Fractal\TransformerAbstract;
use InetStudio\Products\Models\ProductModel;

/**
 * Class BrandTransformer
 * @package InetStudio\Products\Transformers\Back
 */
class BrandTransformer extends TransformerAbstract
{
    private $total;

    /**
     * BrandTransformer constructor.
     *
     * @param $total
     */
    public function __construct($total)
    {
        $this->total = $total;
    }

    /**
     * Подготовка данных для отображения в таблице.
     *
     * @param $data
     *
     * @return array
     *
     * @throws \Throwable
     */
    public function transform(array $data)
    {
        $views = (isset($data['views'])) ? $data['views']['total'] : 0;
        $clicks = (isset($data['clicks'])) ? $data['clicks']['total'] : 0;
        $conversion = ($views == 0) ? 0 : round(($clicks/$views)*100, 2);

        return [
            'brand' => view('admin.module.products::back.partials.datatables.brand', [
                'brand' => $data['brand'],
            ])->render(),
            'percents' => ($this->total == 0) ? 0 : round(($data['references'] / $this->total)*100, 2),
            'references' => $data['references'],
            'views' => $views,
            'views_users' => (isset($data['views'])) ? $data['views']['users'] : 0,
            'clicks' => $clicks,
            'clicks_users' => (isset($data['clicks'])) ? $data['clicks']['users'] : 0,
            'conversion' => $conversion,
            'shops' => view('admin.module.products::back.partials.datatables.shops', [
                'shops' => (isset($data['clicks']['shops'])) ? $data['clicks']['shops'] : [],
            ])->render(),
        ];
    }
}
