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
        return [
            'brand' => view('admin.module.products::back.partials.datatables.brand', [
                'brand' => $data['brand'],
            ])->render(),
            'percents' => ($data['references'] == 0) ? 0 : round(($data['references'] / $this->total)*100, 2),
            'references' => $data['references'],
        ];
    }
}
