<?php

namespace InetStudio\Products\Transformers\Back;

use League\Fractal\TransformerAbstract;
use InetStudio\Products\Models\ProductableModel;

/**
 * Class ProductableTransformer
 * @package InetStudio\Products\Transformers\Back
 */
class ProductableTransformer extends TransformerAbstract
{
    /**
     * Подготовка данных для отображения в таблице.
     *
     * @param ProductableModel $productable
     *
     * @return array
     *
     * @throws \Throwable
     */
    public function transform(ProductableModel $productable)
    {
        return [
            'preview' => view('admin.module.products::back.partials.datatables.preview', [
                'src' => url($productable->product->getFirstMediaUrl('preview', 'preview_admin')),
            ])->render(),
            'title' => $productable->product->title,
            'material_type' => $productable->productable->type,
            'material_title' => view('admin.module.products::back.partials.datatables.productable', [
                'productable' => $productable->productable,
            ])->render(),
            'actions' => view('admin.module.products::back.partials.datatables.actions', [
                'id' => $productable->product->id,
            ])->render(),
        ];
    }
}
