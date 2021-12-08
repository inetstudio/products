<?php

namespace InetStudio\ProductsPackage\Products\Models\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Trait Product.
 */
trait Product
{
    /**
     * Отношение "один к одному" с моделью приза.
     *
     * @return HasOne
     *
     * @throws BindingResolutionException
     */
    public function product(): HasOne
    {
        $productModel = app()->make('InetStudio\ProductsPackage\Products\Contracts\Models\ProductModelContract');

        return $this->belongsTo(
            get_class($productModel),
            'id',
            'product_id'
        );
    }
}
