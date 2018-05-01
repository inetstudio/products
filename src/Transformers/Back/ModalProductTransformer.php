<?php

namespace InetStudio\Products\Transformers\Back;

use League\Fractal\TransformerAbstract;
use InetStudio\Products\Models\ProductModel;
use InetStudio\Products\Contracts\Transformers\Back\ModalProductTransformerContract;

/**
 * Class ModalProductTransformer.
 */
class ModalProductTransformer extends TransformerAbstract implements ModalProductTransformerContract
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
            'brand' => $product->brand,
            'title' => $product->title,
            'description' => $product->description,
            'actions' => view('admin.module.products::back.partials.datatables.actions_modal', [
                'item' => $product,
            ])->render(),
        ];
    }
}
