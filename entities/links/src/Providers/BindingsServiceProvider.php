<?php

namespace InetStudio\ProductsPackage\Links\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class BindingsServiceProvider extends BaseServiceProvider implements DeferrableProvider
{
    public array $bindings = [
        'InetStudio\ProductsPackage\Links\Contracts\DTO\Back\Resource\StoreItemDataContract' => 'InetStudio\ProductsPackage\Links\DTO\Back\Resource\StoreItemData',
        'InetStudio\ProductsPackage\Links\Contracts\DTO\Back\Resource\UpdateItemDataContract' => 'InetStudio\ProductsPackage\Links\DTO\Back\Resource\UpdateItemData',

        'InetStudio\ProductsPackage\Links\Contracts\Models\LinkModelContract' => 'InetStudio\ProductsPackage\Links\Models\LinkModel',
    ];

    public function provides()
    {
        return array_keys($this->bindings);
    }
}
