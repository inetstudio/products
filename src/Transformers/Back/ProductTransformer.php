<?php

namespace InetStudio\Products\Transformers\Back;

use League\Fractal\TransformerAbstract;
use InetStudio\Products\Models\ProductModel;

/**
 * Class ProductTransformer.
 */
class ProductTransformer extends TransformerAbstract
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
            'created_at' => (string) $product->created_at,
            'updated_at' => (string) $product->updated_at,
            'actions' => view('admin.module.products::back.partials.datatables.actions', [
                'id' => $product->id,
            ])->render(),
        ];
    }
}
