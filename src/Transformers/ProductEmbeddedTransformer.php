<?php

namespace Inetstudio\Products\Transformers;

use League\Fractal\TransformerAbstract;
use InetStudio\Products\Models\ProductModel;

class ProductEmbeddedTransformer extends TransformerAbstract
{
    /**
     * @param ProductModel $product
     * @return array
     */
    public function transform(ProductModel $product)
    {
        return [
            'id' => (int) $product->id,
            'preview' => view('admin.module.products::partials.datatables.preview', [
                'src' => url($product->getFirstMediaUrl('preview', 'preview_admin')),
            ])->render(),
            'brand' => $product->brand,
            'title' => $product->title,
            'description' => $product->description,
            'actions' => view('admin.module.products::partials.datatables.actions_embedded', [
                'id' => $product->id,
            ])->render(),
        ];
    }
}
