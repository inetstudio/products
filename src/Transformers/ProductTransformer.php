<?php

namespace Inetstudio\Products\Transformers;

use League\Fractal\TransformerAbstract;
use InetStudio\Products\Models\ProductModel;

class ProductTransformer extends TransformerAbstract
{
    /**
     * @param ProductModel $product
     * @return array
     */
    public function transform(ProductModel $product)
    {
        return [
            'id' => (int) $product->id,
            'preview' => view('admin.module.products::pages.products.datatables.preview', [
                'src' => url($product->getFirstMediaUrl('preview', 'preview_thumb')),
            ])->render(),
            'title' => $product->title,
            'created_at' => (string) $product->created_at,
            'updated_at' => (string) $product->updated_at,
            'actions' => view('admin.module.products::pages.products.datatables.actions', [
                'id' => $product->id,
            ])->render(),
        ];
    }
}
