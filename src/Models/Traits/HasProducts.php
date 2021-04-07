<?php

namespace InetStudio\Products\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use InetStudio\Products\Models\ProductModel;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasProducts
{
    /**
     * The Queued products.
     *
     * @var array
     */
    protected $queuedProducts = [];

    /**
     * Get product class name.
     *
     * @return string
     */
    public static function getProductClassName(): string
    {
        return ProductModel::class;
    }

    /**
     * Get all attached products to the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function products(): MorphToMany
    {
        return $this->morphToMany(static::getProductClassName(), 'productable')->withTimestamps();
    }

    /**
     * Attach the given product(s) to the model.
     *
     * @param int|string|array|\ArrayAccess|ProductModel $products
     */
    public function setProductsAttribute($products)
    {
        if (! $this->exists) {
            $this->queuedProducts = $products;

            return;
        }

        $this->attachProducts($products);
    }

    /**
     * Boot the productable trait for a model.
     */
    public static function bootHasProducts()
    {
        static::created(function (Model $productableModel) {
            if ($productableModel->queuedProducts) {
                $productableModel->attachProducts($productableModel->queuedProducts);
                $productableModel->queuedProducts = [];
            }
        });

        static::deleted(function (Model $productableModel) {
            $productableModel->syncProducts(null);
        });
    }

    /**
     * Get the product list.
     *
     * @param string $keyColumn
     *
     * @return array
     */
    public function productList(string $keyColumn = 'id'): array
    {
        return $this->products()->pluck('title', $keyColumn)->toArray();
    }

    /**
     * Scope query with all the given products.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|string|array|\ArrayAccess|ProductModel $products
     * @param string $column
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAllProducts(Builder $query, $products, string $column = 'id'): Builder
    {
        $products = static::isProductsStringBased($products)
            ? $products : static::hydrateProducts($products)->pluck($column)->toArray();

        collect($products)->each(function ($ingredient) use ($query, $column) {
            $query->whereHas('products', function (Builder $query) use ($ingredient, $column) {
                return $query->where($column, $ingredient);
            });
        });

        return $query;
    }

    /**
     * Scope query with any of the given products.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|string|array|\ArrayAccess|ProductModel $products
     * @param string $column
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAnyProducts(Builder $query, $products, string $column = 'id'): Builder
    {
        $products = static::isProductsStringBased($products)
            ? $products : static::hydrateProducts($products)->pluck($column)->toArray();

        return $query->whereHas('products', function (Builder $query) use ($products, $column) {
            $query->whereIn($column, (array) $products);
        });
    }

    /**
     * Scope query with any of the given products.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|string|array|\ArrayAccess|ProductModel $products
     * @param string $column
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithProducts(Builder $query, $products, string $column = 'id'): Builder
    {
        return static::scopeWithAnyProducts($query, $products, $column);
    }

    /**
     * Scope query without the given Products.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|string|array|\ArrayAccess|ProductModel $products
     * @param string $column
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithoutProducts(Builder $query, $products, string $column = 'id'): Builder
    {
        $products = static::isProductsStringBased($products)
            ? $products : static::hydrateProducts($products)->pluck($column)->toArray();

        return $query->whereDoesntHave('products', function (Builder $query) use ($products, $column) {
            $query->whereIn($column, (array) $products);
        });
    }

    /**
     * Scope query without any Products.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithoutAnyProducts(Builder $query): Builder
    {
        return $query->doesntHave('products');
    }

    /**
     * Attach the given product(s) to the model.
     *
     * @param int|string|array|\ArrayAccess|ProductModel $products
     *
     * @return $this
     */
    public function attachProducts($products)
    {
        static::setProducts($products, 'syncWithoutDetaching');

        return $this;
    }

    /**
     * Sync the given product(s) to the model.
     *
     * @param int|string|array|\ArrayAccess|ProductModel|null $products
     *
     * @return $this
     */
    public function syncProducts($products)
    {
        static::setProducts($products, 'sync');

        return $this;
    }

    /**
     * Detach the given product(s) from the model.
     *
     * @param int|string|array|\ArrayAccess|ProductModel $products
     *
     * @return $this
     */
    public function detachProducts($products)
    {
        static::setProducts($products, 'detach');

        return $this;
    }

    /**
     * Determine if the model has any the given products.
     *
     * @param int|string|array|\ArrayAccess|ProductModel $products
     *
     * @return bool
     */
    public function hasProduct($products): bool
    {
        // Single product id
        if (is_string($products)) {
            return $this->products->contains('id', $products);
        }

        // Single product id
        if (is_int($products)) {
            return $this->products->contains('id', $products);
        }

        // Single product model
        if ($products instanceof ProductModel) {
            return $this->products->contains('id', $products->id);
        }

        // Array of product ids
        if (is_array($products) && isset($products[0]) && is_string($products[0])) {
            return ! $this->products->pluck('id')->intersect($products)->isEmpty();
        }

        // Array of product ids
        if (is_array($products) && isset($products[0]) && is_int($products[0])) {
            return ! $this->products->pluck('id')->intersect($products)->isEmpty();
        }

        // Collection of product models
        if ($products instanceof Collection) {
            return ! $products->intersect($this->product->pluck('id'))->isEmpty();
        }

        return false;
    }

    /**
     * Determine if the model has any the given products.
     *
     * @param int|string|array|\ArrayAccess|ProductModel $products
     *
     * @return bool
     */
    public function hasAnyProduct($products): bool
    {
        return static::hasProduct($products);
    }

    /**
     * Determine if the model has all of the given products.
     *
     * @param int|string|array|\ArrayAccess|ProductModel $products
     *
     * @return bool
     */
    public function hasAllProducts($products): bool
    {
        // Single product id
        if (is_string($products)) {
            return $this->product->contains('id', $products);
        }

        // Single product id
        if (is_int($products)) {
            return $this->product->contains('id', $products);
        }

        // Single product model
        if ($products instanceof ProductModel) {
            return $this->product->contains('id', $products->id);
        }

        // Array of product ids
        if (is_array($products) && isset($products[0]) && is_string($products[0])) {
            return $this->product->pluck('id')->count() === count($products)
                && $this->product->pluck('id')->diff($products)->isEmpty();
        }

        // Array of product ids
        if (is_array($products) && isset($products[0]) && is_int($products[0])) {
            return $this->product->pluck('id')->count() === count($products)
                && $this->product->pluck('id')->diff($products)->isEmpty();
        }

        // Collection of product models
        if ($products instanceof Collection) {
            return $this->product->count() === $products->count() && $this->product->diff($products)->isEmpty();
        }

        return false;
    }

    /**
     * Set the given product(s) to the model.
     *
     * @param int|string|array|\ArrayAccess|ProductModel $products
     * @param string $action
     */
    protected function setProducts($products, string $action)
    {
        // Fix exceptional event name
        $event = $action === 'syncWithoutDetaching' ? 'attach' : $action;

        // Hydrate products
        $products = static::hydrateProducts($products)->pluck('id')->toArray();

        // Fire the product syncing event
        static::$dispatcher->dispatch("inetstudio.products.{$event}ing", [$this, $products]);

        // Set products
        $this->products()->$action($products);

        // Fire the Ingredient synced event
        static::$dispatcher->dispatch("inetstudio.products.{$event}ed", [$this, $products]);
    }

    /**
     * Hydrate products.
     *
     * @param int|string|array|\ArrayAccess|ProductModel $products
     *
     * @return \Illuminate\Support\Collection
     */
    protected function hydrateProducts($products)
    {
        $isProductsStringBased = static::isProductsStringBased($products);
        $isProductsIntBased = static::isProductsIntBased($products);
        $field = $isProductsStringBased ? 'id' : 'id';
        $className = static::getProductClassName();

        return $isProductsStringBased || $isProductsIntBased
            ? $className::query()->whereIn($field, (array) $products)->get() : collect($products);
    }

    /**
     * Determine if the given product(s) are string based.
     *
     * @param int|string|array|\ArrayAccess|ProductModel $products
     *
     * @return bool
     */
    protected function isProductsStringBased($products)
    {
        return is_string($products) || (is_array($products) && isset($products[0]) && is_string($products[0]));
    }

    /**
     * Determine if the given product(s) are integer based.
     *
     * @param int|string|array|\ArrayAccess|ProductModel $products
     *
     * @return bool
     */
    protected function isProductsIntBased($products)
    {
        return is_int($products) || (is_array($products) && isset($products[0]) && is_int($products[0]));
    }
}
