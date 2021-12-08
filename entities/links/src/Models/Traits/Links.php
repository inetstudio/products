<?php

namespace InetStudio\ProductsPackage\Links\Models\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;

trait Links
{
    public function links(): HasMany
    {
        $linkModel = resolve('InetStudio\ProductsPackage\Links\Contracts\Models\LinkModelContract');

        return $this->hasMany(
            $linkModel::class,
            'product_id',
            'id'
        );
    }
}
