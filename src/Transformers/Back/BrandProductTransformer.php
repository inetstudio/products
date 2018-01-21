<?php

namespace InetStudio\Products\Transformers\Back;

use League\Fractal\TransformerAbstract;
use InetStudio\Products\Models\ProductModel;

/**
 * Class BrandProductTransformer
 * @package InetStudio\Products\Transformers\Back
 */
class BrandProductTransformer extends TransformerAbstract
{
    /**
     * Подготовка данных для отображения в таблице.
     *
     * @param ProductModel $product
     *
     * @return array
     *
     * @throws \Throwable
     */
    public function transform(ProductModel $product)
    {
        return [
            'id' => (int) $product->id,
            'preview' => view('admin.module.products::back.partials.datatables.preview', [
                'src' => url($product->getFirstMediaUrl('preview', 'preview_admin')),
            ])->render(),
            'title' => $product->title,
            'material_type' => '',
            'material_title' => '',
            'actions' => view('admin.module.products::back.partials.datatables.actions', [
                'id' => $product->id,
            ])->render(),
        ];
    }
}
